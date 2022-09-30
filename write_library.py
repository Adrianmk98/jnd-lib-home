#!/usr/bin/env python

from babel.support import Translations
import datetime
import jinja2
import locale
import requests


def get_guides(lang="en-CA"):
    "Retrieve guides from research guides database"

    url = "https://biblio.laurentian.ca/research/guides-json"
    if lang == "fr-CA":
        url = "https://biblio.laurentian.ca/research/fr/guides-json"

    r = requests.post(url)
    guides = r.json()

    return guides


def get_databases(lang="en-CA"):
    "Retrieve databases from research guides database"

    url = "https://biblio.laurentian.ca/research/databases-a-z?json"
    if lang == "fr-CA":
        url = "https://biblio.laurentian.ca/research/fr/databases-a-z?json"

    r = requests.post(url)
    databases = r.json()

    return databases


def get_hours(lang="en-CA"):
    "Retrieve hours from library hours service"

    l10n = lang.replace("-", "_")
    locale.setlocale(locale.LC_ALL, f"{l10n}.UTF-8")

    libraries = {
        "Archives": {
            "name": "Archives",
            "url": "https://biblio.laurentian.ca/research/guides/archives",
        },
        "CRC": {
            "name": "Education Resource Centre",
            "url": "",
        },
        "JND": {
            "name": "J.N. Desmarais",
            "url": "",
        },
        "MRC": {
            "name": "Music Resource Centre",
            "url": "",
        },
        "SoA": {
            "name": "Architecture",
            "url": "https://biblio.laurentian.ca/research/guides/architecture",
        },
    }
    closed = "Closed"
    hour_format = "{} - {}"

    if lang == "fr-CA":
        libraries = {
            "Archives": {
                "name": "Archives",
                "url": "https://biblio.laurentian.ca/research/fr/guides/archives",
            },
            "CRC": {
                "name": "Centre de Ressources en Éducation",
                "url": "",
            },
            "JND": {
                "name": "J.N. Desmarais",
                "url": "",
            },
            "MRC": {
                "name": "Centre de Ressources en Musique",
                "url": "",
            },
            "SoA": {
                "name": "Architecture",
                "url": "https://biblio.laurentian.ca/research/fr/guides/architecture",
            },
        }
        closed = "Fermée"
        hour_format = "{} à {}"

    today = datetime.date.today().isoformat()

    # If we ask for dates for today, we might not get today's dates(!)
    yesterday = (datetime.date.today() - datetime.timedelta(days=1)).isoformat()
    post_data = {
        "direction": "next",
        "method": "getHours",
        "lang": lang[0:2],
        "day": yesterday,
    }
    r = requests.post(
        "https://laurentian.ca/sites/all/custom/library/library_hours.php",
        data=post_data,
    )
    hours_data = r.json()

    hours = []
    for x in hours_data:
        if x["day"] == today:
            for lib in sorted(
                libraries, key=lambda lib: locale.strxfrm(libraries[lib]["name"])
            ):
                if lib not in x["libraries"] and lib not in ("Archives", "CRC"):
                    # CRC and MRC temporarily disabled until we have separate pages & hours
                    continue

                # Hack for multiple sets of hours for SoA start of fall
                #                if (libraries[lib]["name"] == "Architecture"):
                #                    english_hours = "9:00 AM - 12:00 PM, 1:00 PM - 4:30 PM"
                #                    french_hours = "9h00 à 12h00, 13h00 à 16h30"
                #                    hack_hours_architecture(hours, libraries, lib, lang, closed, english_hours, french_hours)
                #                    continue
                #
                # Hack for Archives
                if lib == "Archives":
                    hack_hours_archives(hours, libraries, lib, lang, closed)
                    continue

                # Hack for Education Resource Centre
                if lib == "CRC":
                    hack_hours_education(hours, libraries, lib, lang, closed)
                    continue

                if x["libraries"][lib]["closed"] == 1:
                    hours.append(
                        {
                            "name": libraries[lib]["name"],
                            "hours": closed,
                            "url": libraries[lib]["url"],
                        }
                    )
                else:
                    o = x["libraries"][lib]["open"]
                    c = x["libraries"][lib]["close"]

                    o = format_time(o, lang)
                    c = format_time(c, lang)
                    hours.append(
                        {
                            "name": libraries[lib]["name"],
                            "hours": hour_format.format(o, c),
                            "url": libraries[lib]["url"],
                        }
                    )
    return hours


def hack_hours_architecture(
    hours, libraries, lib, lang, closed, english_hours, french_hours
):
    "Used when we need split hours because IT calendar can't handle them"

    if lang == "en-CA":
        h = english_hours
    else:
        h = french_hours

    # Closed on weekends for now
    if datetime.date.today().weekday() > 4:
        h = closed

    hours.append(
        {
            "name": libraries[lib]["name"],
            "hours": h,
            "url": libraries[lib]["url"],
        }
    )


def hack_hours_education(hours, libraries, lib, lang, closed):
    "Add Education hours until we can pull from IT calendar"

    english_hours = "9:00 AM - 12:00 PM, 1:00 PM - 4:30 PM"
    french_hours = "9h00 à 12h00, 13h00 à 16h30"
    if lang == "en-CA":
        if datetime.date.today().weekday() == 0:
            h = "9:00 AM - 4:30 PM"
        elif datetime.date.today().weekday() == 1:
            h = "10:00 AM - 4:30 PM"
        elif datetime.date.today().weekday() == 2:
            h = "10:00 AM - 3:30 PM"
        elif datetime.date.today().weekday() == 3:
            h = "9:00 AM - 4:30 PM"
        elif datetime.date.today().weekday() == 4:
            h = "9:00 AM - 3:30 PM"
        elif datetime.date.today().weekday() > 4:
            h = closed
    else:
        if datetime.date.today().weekday() == 0:
            h = "9h00 à 16h30"
        elif datetime.date.today().weekday() == 1:
            h = "10h00 à 16h30"
        elif datetime.date.today().weekday() == 2:
            h = "10h00 à 15h30"
        elif datetime.date.today().weekday() == 3:
            h = "9h00 à 16h30"
        elif datetime.date.today().weekday() == 4:
            h = "9h00 à 15h30"
        elif datetime.date.today().weekday() > 4:
            h = closed

    hours.append(
        {
            "name": libraries["CRC"]["name"],
            "hours": h,
            "url": libraries["CRC"]["url"],
        }
    )


def hack_hours_archives(hours, libraries, lib, lang, closed):
    "Hardcode archives hours because IT calendar can't handle split hours"

    h = "10:00 AM - 12:00 PM, 1:00 PM - 4:00 PM"
    if lang == "fr-CA":
        h = "10h00 à 12h00, 13h00 à 16h00"

    # Archives are open Tuesday through Friday
    if datetime.date.today().weekday() == 0 or datetime.date.today().weekday() > 4:
        h = closed

    hours.append(
        {
            "name": libraries["Archives"]["name"],
            "hours": h,
            "url": libraries["Archives"]["url"],
        }
    )


def format_time(hour, lang="en-CA"):
    "Format time according to locale preference"

    if len(hour) == 4:
        hour = f"0{hour}"
    if lang.startswith("fr"):
        return datetime.time.fromisoformat(hour).strftime("%-Hh%M")
    else:
        return datetime.time.fromisoformat(hour).strftime("%-I:%M %p")


def get_news(lang="en-CA", max_items=4, max_age=30):
    "Pull news items from Library & Archives feed"

    import atoma

    url = "https://biblio.laurentian.ca/research/news.xml"
    news_link = "https://biblio.laurentian.ca/research/news"
    news_heading = "News"

    if lang.startswith("fr"):
        url = "https://biblio.laurentian.ca/research/fr/news.xml"
        news_link = "https://biblio.laurentian.ca/research/fr/nouvelles"
        news_heading = "Nouvelles"

    r = requests.get(url)
    feed = atoma.parse_rss_bytes(r.content)

    news_items = []
    x = 0
    for item in feed.items:
        if x == max_items or (
            x > 0
            and item.pub_date.timestamp()
            < (datetime.datetime.today() - datetime.timedelta(days=max_age)).timestamp()
        ):
            break
        news_items.append(
            {"title": item.title, "url": item.link, "published": item.pub_date.date()}
        )
        x = x + 1

    return news_items


def generate_page(lang="en-CA", filename="prod/index.html"):
    "Generate the library & archives home page"

    env = jinja2.Environment(
        autoescape=jinja2.select_autoescape(["html"]),
        extensions=["jinja2.ext.i18n"],
        loader=jinja2.FileSystemLoader("./templates/"),
        trim_blocks=True,
        lstrip_blocks=True,
    )

    loc = lang.replace("-", "_")
    translations = Translations.load("locale", [loc])
    env.install_gettext_translations(translations)

    template = env.get_template("library.html")
    hours = get_hours(lang)
    news = get_news(lang)
    databases = get_databases(lang)
    guides = get_guides(lang)
    libhelp = 643
    if lang == "fr-CA":
        libhelp = 531

    ctx = {
        "lang": lang,
        "hours": hours,
        "news": news,
        "databases": databases,
        "guides": guides,
        "libhelp": libhelp,
    }
    with open(filename, mode="w", encoding="utf-8") as outf:
        outf.write(template.render(ctx))


if __name__ == "__main__":
    generate_page()
    generate_page("fr-CA", "prod/index_fr.html")
