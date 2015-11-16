# Book migration

`book_migration` is an example Drupal 8 compatible module that imports content from CSV files.

## Installation

1. Install Drupal 8 using `Standard` profile.
2. Download the latest codebase of `migrate_source_csv` contrib module into `modules/contrib/migrate_source_csv`.
3. Download the latest codebase of `migrate_tools` contrib module into `modules/contrib/migrate_tools`.
4. Download the latest codebase of `migrate_plus` contrib module into `modules/contrib/migrate_plus`. You need to
checkout the `split` branch to avoid conflicts with standalone modules.
5. Enable `book_migration` module.
6. Install Drupal 8 compatible `drush`.  

## Usage

1. Run `drush ms` to see all migrations.
2. Run `drush mi --all` to import all content.
