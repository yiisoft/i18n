# Yii Internationalization Library Change Log

## 1.2.3 under development

- Enh #79: Explicitly import classes in "use" section (@mspirkov)

## 1.2.2 November 29, 2025

- Chg #75, #76: Change PHP constraint in `composer.json` to `8.0 - 8.5` (@vjik)

## 1.2.1 June 10, 2023

- Bug #56: Fix `LocaleProvider::isDefaultLocale()` giving a wrong result if locale is set explicitly to the one matching default (@samdark)

## 1.2.0 June 04, 2023

- New #55: Add `LocaleProvider` (@samdark)
- Chg #55: Raise major PHP version to 8 (@samdark)

## 1.1.0 November 05, 2021

- New #33: Add support for keywords `hours`, `colnumeric`, and `colcasefirst`. These
  keywords are part of the [ECMAScript 2022 Internationalization API Specification
  (ECMA-402 9th Edition)](https://tc39.es/ecma402/), and supporting them allows
  for better cross-communication between PHP and JavaScript layers.
  - `hours` defines an hour cycle for the locale (i.e. `h11`, `h12`, `h23`, `h24`).
    For more information see the [key/type definition for the Unicode Hour Cycle
    Identifier](https://www.unicode.org/reports/tr35/tr35-61/tr35.html#UnicodeHourCycleIdentifier).
  - `colnumeric` and `colcasefirst` are both collation settings defined as part
    of the [Unicode Locale Data Markup Language](https://www.unicode.org/reports/tr35/tr35-61/tr35-collation.html#Collation_Settings) (@ramsey)

## 1.0.0 December 25, 2020

- Initial release.
