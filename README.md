# Migrate source example

`migrate_source_example` is a module that contains a set of sub-modules that provide content migrations from different
sources.

Currently the project features migrations from following sources:

1. External (non-Drupal) database tables.
2. CSV files;
3. XML files;
4. JSON resources.

## Installation

1. Install Drupal 8 compatible `drush`.
2. Install Drupal 8 using `Standard` profile.
3. Download `migrate_tools` contrib module into `modules/contrib/migrate_tools` (see [instructions](https://www.drupal.org/project/migrate_tools/git-instructions)).
4. Download `migrate_plus` contrib module into `modules/contrib/migrate_plus` (see [instructions](https://www.drupal.org/project/migrate_plus/git-instructions)).
5. Enable `migrate_source_example` module (`drush en migrate_source_example`).

### Installation of DB migration example module
1. Enable `migrate_source_example_db` module (`drush en migrate_source_example_db`).

### Installation of CSV migration example module
1. Download `migrate_source_csv` contrib module into `modules/contrib/migrate_source_csv` (see [instructions](https://www.drupal.org/project/migrate_source_csv/git-instructions)).
2. Enable `migrate_source_example_csv` module (`drush en migrate_source_example_csv`).

### Installation of XML migration example module
1. Enable `migrate_source_example_xml` module (`drush en migrate_source_example_xml`).

### Installation of JSON migration example module
1. Enable `migrate_source_example_json` module (`drush en migrate_source_example_json`).

### Installation of spreadsheet migration example module
1. Download `migrate_drush` contrib module into `modules/contrib/migrate_drush` (see [instructions](https://www.drupal.org/project/migrate_drush/git-instructions)).
2. Enable `migrate_source_example_spreadsheet` module (`drush en migrate_source_example_spreadsheet`).

## Usage

1. Run `drush ms` to see all migrations.
2. Run `drush mi --group=[GROUP]` to import content from specific example group.

## Special usage of JSON migration example

JSON migration source plugin requires an absolute URL of a JSON resource to be set in migration .yml file due to
an assumption that JSON resources are remote. It means that for JSON migration to work, a base url of the site
needs to be provided to migration system.

Run `drush mi --group=migrate_source_example_json --uri=[BASE_URL]`, where `[BASE_URL]` is an absolute path to your
site.

## Special usage of spreadsheet migration example

Spreadsheet migrations are created as plugin migrations, therefore they cannot be interacted with using commands
provided by `migrate_tools` modules. `migrate_drush` provides `migrate_drush_run` drush command, which can import
or rollback each migration individually one-by-one.

Run the following commands to import data:

1. `drush migrate_drush_run migrate_source_example_spreadsheet_user --method=import` to import users;

Run the following commands to rollback data:

1. `drush migrate_drush_run migrate_source_example_spreadsheet_user --method=rollback` to rollback user migration;

## Data source

Example content is synced with a [Google Spreadsheet](https://goo.gl/Iq2Tk6).
