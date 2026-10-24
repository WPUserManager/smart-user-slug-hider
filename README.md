# Smart User Slug Hider

[Smart User Slug Hider](https://wordpress.org/plugins/smart-user-slug-hider/) on wordpress.org. Built by
Peter Raschendorfer ([petersplugins](https://profiles.wordpress.org/petersplugins/)),
adopted by WP User Manager in October 2026. Its history up to 4.0.6 is in
[wordpress.org SVN](https://plugins.svn.wordpress.org/smart-user-slug-hider/).

## Releasing

1. Bump the version in the plugin header (`smart-user-slug-hider.php`), `'version'` in
   `loader.php`, and `readme.txt` (`Stable tag` and a changelog entry).
2. Merge `develop` into `master` and tag `vX.Y.Z` on `master`.

The tag runs `.github/workflows/release.yml`: the shared
[deploy workflow](https://github.com/WPUserManager/.github) builds the zip from
everything not in `.distignore`, checks all three versions match the tag,
deploys to wordpress.org SVN (org secrets `SVN_USERNAME`, `SVN_PASSWORD`) and
creates the GitHub release. Pull requests run the same build as a dry run.

## Tests

`tests/test-blowfish.php` checks the plain PHP Blowfish (used where OpenSSL 3 has no
Blowfish) against OpenSSL's own BF-ECB. The file says how to run it with OpenSSL 3's
legacy provider. `tests/test-author-link.php` checks that only the user name at the end
of an author URL is replaced. Both run with plain `php`; `tests/` is left out of the release zip.

## Listing art

`.wordpress-org/` holds the icon and banner for the wordpress.org listing. They live
in the SVN `assets/` folder, not in the plugin, so a release doesn't upload them:
commit changes to `https://plugins.svn.wordpress.org/smart-user-slug-hider/assets/`.
