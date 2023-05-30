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
        "ERC": {
            "name": "Education Resource Centre",
            "url": "",
        },
        "JND": {
            "name": "J.N. Desmarais",
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
            "ERC": {
                "name": "Centre de Ressources en Éducation",
                "url": "",
            },
            "JND": {
                "name": "J.N. Desmarais",
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
                if lib not in x["libraries"]:
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
                    format_hours(x, hours, libraries, lib, lang, closed, hour_format)
    return hours


def format_hours(x, hours, libraries, lib, lang, closed, hour_format):
    "Check for an _aft variant to accomodate split hours"

    o = x["libraries"][lib]["open"]
    c = x["libraries"][lib]["close"]

    o = format_time(o, lang)
    c = format_time(c, lang)

    # Check for afternoon hours for each library
    aft_hours = lib + "_aft"
    if aft_hours in x["libraries"] and x["libraries"][aft_hours]["closed"] != 1:
        oa = x["libraries"][aft_hours]["open"]
        ca = x["libraries"][aft_hours]["close"]

        oa = format_time(oa, lang)
        ca = format_time(ca, lang)

        hours.append(
            {
                "name": libraries[lib]["name"],
                "hours": (hour_format.format(o, c) + ", " + hour_format.format(oa, ca)),
                "url": libraries[lib]["url"],
            }
        )
    else:
        # Architecture summer hours hack
        if lib == "SoA":
            o = "8:30"
            c = "12:00"
            oa = "1:00"
            ca = "4:00"
            o = format_time(o, lang)
            c = format_time(c, lang)
            oa = format_time(oa, lang)
            ca = format_time(ca, lang)

            hours.append(
                {
                    "name": libraries[lib]["name"],
                    "hours": (
                        hour_format.format(o, c) + ", " + hour_format.format(oa, ca)
                    ),
                    "url": libraries[lib]["url"],
                }
            )
            return

        hours.append(
            {
                "name": libraries[lib]["name"],
                "hours": hour_format.format(o, c),
                "url": libraries[lib]["url"],
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
