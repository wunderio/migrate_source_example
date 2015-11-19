# CSV migration example

`csv_migration_example` is an example Drupal 8 compatible module that provides content migration from CSV files.

## Installation

1. Install Drupal 8 compatible `drush`.
2. Install Drupal 8 (RC4 or later) using `Standard` profile.
3. Download the latest codebase of `migrate_source_csv` contrib module (commit `4efbb9b`) into `modules/contrib/migrate_source_csv` (see [instructions](https://www.drupal.org/project/migrate_source_csv/git-instructions)).
4. Download the latest codebase of `migrate_tools` contrib module (commit `fa10b36`) into `modules/contrib/migrate_tools` (see [instructions](https://www.drupal.org/project/migrate_tools/git-instructions)).
5. Download the latest codebase of `migrate_plus` contrib module (commit `1039bfc`) into `modules/contrib/migrate_plus` (see [instructions](https://www.drupal.org/project/migrate_plus/git-instructions)).
6. Enable `csv_migration_example` module (`drush en csv_migration_example`).
  
## Usage

1. Run `drush ms` to see all migrations.
2. Run `drush mi --all` to import all content.

## Data source

CSV file content is synced from a [Google Spreadsheet](https://goo.gl/oG0jz0).
