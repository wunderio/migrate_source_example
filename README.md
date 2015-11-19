# CSV migration example

`csv_migration_example` is an example Drupal 8 compatible module that provides content migration from CSV files.

## Installation

1. Install Drupal 8 compatible `drush`.
2. Install Drupal 8 using `Standard` profile.
3. Download `migrate_source_csv` contrib module into `modules/contrib/migrate_source_csv` (see [instructions](https://www.drupal.org/project/migrate_source_csv/git-instructions)).
4. Download `migrate_tools` contrib module into `modules/contrib/migrate_tools` (see [instructions](https://www.drupal.org/project/migrate_tools/git-instructions)).
5. Download `migrate_plus` contrib module into `modules/contrib/migrate_plus` (see [instructions](https://www.drupal.org/project/migrate_plus/git-instructions)).
6. Enable `csv_migration_example` module (`drush en csv_migration_example`).
  
## Usage

1. Run `drush ms` to see all migrations.
2. Run `drush mi --all` to import all content.

## Data source

CSV file content is synced from a [Google Spreadsheet](https://goo.gl/Iq2Tk6).
