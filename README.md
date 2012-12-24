vkusnoe-delo
============

Cooking social network based on Laravel

# Информация для разработчиков

## Цель проекта
Целью проекта является создание социальной сети для людей, любящих готовить

## ТЗ
### Необходимый функционал:

 - Возможность регистрации пользователя, в т. ч. и с пом. протокола OAuth посредством следующих ресурсов:
  - [VK][vk]
  - [Twitter][twit]
  - [Facebook][fb]
 - Возможность добавления пользователем информации о себе, такой как:
  - имя, пол, возраст
  - фотография профиля
  - место проживания
  - контактные данные
  - "о себе"
 - Возможность добавления пользователем рецептов
 - Рецепт должен содержать следующие поля и атрибуты:
  - Название
  - Список ингредиентнов
  - Кол-во калорий, энергетическая ценность *(опц.)*
  - Миниатюра рецепта *(опц.)*
  - Описание процесса приготовления *(HTML)*
  - Видеорецепт *(опц.)*
 - Возможность добавления комментариев к рецепту.
 - Кнопки "Мне нравится" a la [VK][vk]/[Facebook][fb] или рейтинг a la [Хабрахабр][habr], применимые как к рецептам, так и к комментариям
 - Отдельный тип данных - **Новость**, которым должны оперировать только администраторы. К новостям так же могут быть применимы лайки и комментарии.
 - Распределение рецептов по категориям.
 - Поиск рецептов, комментариев и новостей *(возм. использование Sphinx)*
 - Возможность настройки **ленты пользователя** a-la [Хабрахабр][habr]

#### Кроме того:
 - Панель администратора с возможностью управления пользователями, новостями, модерированием рецептов и комментариев и т. д.
 - Система **разрешений (capabilities)** для реализации различных уровней доступа для администраторов/модераторов

### Дополнительный функционал: схемы монетизации

 - Встраивание контекстной рекламы и AdRiver
 - Реализация конкурсов среди пользователей с денежными призами
 - Возможность добавления т. н. платного **корпоративного блога** (для ресторанов и шев-поваров, например), или же отдельных рекламных рецептов.

### Дизайн

Дизайн-макетами обмениваться предполагается с помощью почты

### Техническая реализация

Техническую спецификацию проекта ищите в wiki


[vk]: http://vk.com
[fb]: http://facebook.com
[twit]: http://twitter.com
[habr]: http://habrahabr.ru