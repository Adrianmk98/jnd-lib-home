#!/usr/bin/env python

from babel.support import Translations
import datetime
import jinja2
import requests

def get_hours(lang="en_CA"):
    "Retrieve hours from library hours service"

    import locale
    locale.setlocale(locale.LC_ALL, lang)

    libraries = {
        "Archives": "Archives",
        "CRC": "Curriculum Resource Centre",
        "JND": "J.N. Desmarais",
        "JWT": "J.W. Tate, Huntington",
        "MRC": "Music Resource Centre",
        "SoA": "Architecture",
        "UoS": "University of Sudbury",
    }
    closed = "Closed"
    hour_format = "{} - {}"

    if lang == "fr_CA":
        libraries = {
            "Archives": "Archives",
            "CRC": "Centre de Ressources en Éducation",
            "JND": "J.N. Desmarais",
            "JWT": "J.W. Tate, Huntington",
            "MRC": "Centre de Ressources en Musique",
            "SoA": "Architecture",
            "UoS": "University of Sudbury",
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
        "https://laurentian.ca/sites/all/custom/library/library_hours.php", data=post_data
    )
    hours_data = r.json()

    hours = []
    for x in hours_data:
        if x["day"] == today:
            for lib in sorted(libraries, key=lambda lib: locale.strxfrm(libraries[lib])):
                if lib not in x["libraries"] or x["libraries"][lib]["closed"] == 0:
                    hours.append({ "name": libraries[lib], "hours": closed})
                else:
                    o = format_time(x['libraries'][lib]['open'], lang)
                    c = format_time(x['libraries'][lib]['close'], lang)
                    hours.append({ "name": libraries[lib], "hours": hour_format.format(o, c)})
    return(hours)

def format_time(hour, lang="en_CA"):
    "Format time according to locale preference"

    if len(hour) == 4:
        hour = f"0{hour}"
    if lang.startswith("fr"):
        return datetime.time.fromisoformat(hour).strftime("%Hh%M")
    else:
        return datetime.time.fromisoformat(hour).strftime("%I:%M %p")

def get_news(lang="en_CA", max_items=4, max_age=30):
    "Pull news items from Library & Archives feed"

    import atoma

    url = "https://biblio.laurentian.ca/research/news.xml"
    news_link = "https://biblio.laurentian.ca/research/news"
    news_heading = "News"

    if lang.startswith("fr"):
        url = 'https://biblio.laurentian.ca/research/fr/news.xml'
        news_link = "https://biblio.laurentian.ca/research/fr/nouvelles"
        news_heading = "Nouvelles"

    r = requests.get(url)
    feed = atoma.parse_rss_bytes(r.content)

    news_items = []
    x = 0
    for item in feed.items:
        if x == max_items or (x > 0 and item.pub_date.timestamp() < (datetime.datetime.today() - datetime.timedelta(days=max_age)).timestamp()):
            break
        news_items.append({"title": item.title, "url": item.link, "published": item.pub_date.date()})
        x = x + 1
    
    return news_items

def generate_page(lang='en_CA', filename='index.html'):
    "Generate the library & archives home page"

    env = jinja2.Environment(autoescape=jinja2.select_autoescape(['html']), extensions=['jinja2.ext.i18n'], loader=jinja2.FileSystemLoader('./templates/'), trim_blocks=True, lstrip_blocks=True)

    translations = Translations.load('locale', [lang])
    env.install_gettext_translations(translations)

    template = env.get_template('library.html')
    hours = get_hours(lang)
    news = get_news(lang)
    ctx = { "hours": hours, "news": news, }
    with open(filename, mode="w", encoding="utf-8") as outf:
        outf.write(template.render(ctx))

if __name__ == '__main__':
    generate_page()
    generate_page("fr_CA", "index.html.fr")

