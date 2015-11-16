# Book migration

`book_migration` is an example Drupal 8 compatible module that imports content from CSV files.

## Installation

1. Install Drupal 8 compatible `drush`.
2. Install Drupal 8 using `Standard` profile.
3. Download the latest codebase of `migrate_source_csv` contrib module into `modules/contrib/migrate_source_csv` (see [instructions](https://www.drupal.org/project/migrate_source_csv/git-instructions)).
4. Download the latest codebase of `migrate_tools` contrib module into `modules/contrib/migrate_tools` (see [instructions](https://www.drupal.org/project/migrate_tools/git-instructions)).
5. Download the latest codebase of `migrate_plus` contrib module into `modules/contrib/migrate_plus`. You need to
checkout the `split` branch to avoid conflicts with standalone modules (see [instructions](https://www.drupal.org/node/2202391/git-instructions/split)).
6. Enable `book_migration` module (`drush en book_migration`).
  
## Usage

1. Run `drush ms` to see all migrations.
2. Run `drush mi --all` to import all content.
