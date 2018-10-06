# GitHub webhook-based deploy helper

Uses GitHub webhooks to auto-sync web server directories with GitHub repositories. Hooks into the `push` event. E.g., if you associate a WordPress theme on a staging server with the `staging` branch of its GitHub repo, the copy of the theme on the staging server will automatically update every time you push to the `staging` branch on GitHub.

I created this tool to make it easier for me to work with a specific server environment. It may not work as intended in other environments, and it's probably not wise for anyone other than me to use it at all. If you do use it, be careful.

## Instructions

1. Fork this repository. Make your copy private because you'll be entering information that shouldn't be public.
2. On the web server, clone your fork into a location where `index.php` can be publicly accessed. On WordPress sites, `wp-content/plugins` is one possible location.
3. Add a GitHub webhook -- on just the `push` event -- with the location of the directory's `index.php` as the payload URL. Set the webhook content type to `application/json`. Enter a secure secret for verification.
4. Update `secret.php` with the secret string you created for step 3.
5. Update `repos.php` with details about the repos you want to sync. Repos without the required details will be ignored.
6. For basic security, move those two files to a location on your server outside the public directory, and modify `run.php` to load them from that location.
