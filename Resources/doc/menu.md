# Admin panel menu

Menu is displayed in the upper part of admin panel, on the black navigation bar.
By default menu is empty and you should configure it in ``app/config/admin_menu.yml`` file

```
# app/config/admin_menu.yml

# Your application has following admin elements: news, subscriber, home_page, about_us_page

menu:
  news:
    label: admin.news.name
  subscriber_item:
    element_id: subscriber
    label: admin.subscriber.name
  group:
    label: admin.group.name
    children:
      home_page:
       label: admin.home_page.name
      about_us_page:
        label: admin.about_us_page.name
```

About menu will display link to admin element with id "news" and dropdown button that
have links to elements with "home_page" and "about_us_page" id.

## Translating labels

Labels are translated:

```
# app/config/admin_menu.yml

menu:
  news:
    label: admin.news.name
```

```
# app/Resources/translations/messages.en.yml

admin:
  news:
    name: News
```

[Back to index](index.md)
