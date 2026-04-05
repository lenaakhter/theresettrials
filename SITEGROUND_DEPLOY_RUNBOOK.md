# SiteGround Deploy Runbook

Use these commands in this exact order.

## 1) Local machine (PowerShell)

Run from your project folder:

```powershell
cd C:\Users\lenaa\Documents\theresettrials
git add .
git commit -m "Deploy latest updates"
git push origin main
```

If there is nothing to commit, skip the commit command and continue.

Create deploy archive and upload to SiteGround:

```powershell
git archive --format=tar.gz -o deploy.tar.gz HEAD
scp -i "$env:USERPROFILE\.ssh\lena_new" -P 18765 .\deploy.tar.gz u3012-emeadboaxb0v@ssh.theresettrials.com:~/www/theresettrials.com/deploy.tar.gz
```

If you created or changed post cover images or resource images locally, upload those separately too.
`git archive` does not include untracked uploaded files:

```powershell
ssh -i "$env:USERPROFILE\.ssh\lena_new" -p 18765 u3012-emeadboaxb0v@ssh.theresettrials.com "mkdir -p ~/www/theresettrials.com/public_html/images/uploads"
ssh -i "$env:USERPROFILE\.ssh\lena_new" -p 18765 u3012-emeadboaxb0v@ssh.theresettrials.com "mkdir -p ~/www/theresettrials.com/public_html/images/uploads/resources"
scp -i "$env:USERPROFILE\.ssh\lena_new" -P 18765 -r .\public\images\uploads\posts u3012-emeadboaxb0v@ssh.theresettrials.com:~/www/theresettrials.com/public_html/images/uploads/
scp -i "$env:USERPROFILE\.ssh\lena_new" -P 18765 -r .\public\images\uploads\resources u3012-emeadboaxb0v@ssh.theresettrials.com:~/www/theresettrials.com/public_html/images/uploads/
```

## 2) Connect to SiteGround

```powershell
ssh -o ServerAliveInterval=30 -o ServerAliveCountMax=6 -i "$env:USERPROFILE\.ssh\lena_new" -p 18765 u3012-emeadboaxb0v@ssh.theresettrials.com
```

## 3) Server deploy commands (SSH session)

```bash
cd ~/www/theresettrials.com/laravel-app
tar -xzf ~/www/theresettrials.com/deploy.tar.gz
rm ~/www/theresettrials.com/deploy.tar.gz

# Safety pre-checks (verify production is using the expected DB/session setup)
php artisan tinker --execute="echo 'DB: '.config('database.default').PHP_EOL; echo 'SESSION: '.config('session.driver').PHP_EOL;"

# Stop immediately if DB is not mysql/mariadb (prevents writing to wrong DB like sqlite)
# Use single quotes around --execute to avoid Bash history expansion on '!'.
php artisan tinker --execute='if (! in_array(config("database.default"), ["mysql","mariadb"], true)) { echo "ABORT: unexpected DB connection".PHP_EOL; exit(1); }'


composer install --no-dev --optimize-autoloader
php artisan migrate --force

# Do NOT run optimize:clear in normal deploys; it can flush caches/sessions depending on driver.
php artisan config:cache
php artisan route:cache
php artisan view:cache

npm ci
npm run build
rsync -av --delete public/build/ ~/www/theresettrials.com/public_html/build/
rsync -av public/css/ ~/www/theresettrials.com/public_html/css/
rsync -av --exclude 'uploads/' public/images/ ~/www/theresettrials.com/public_html/images/
# If you changed local uploads and did not run the earlier scp step, sync resource uploads too:
# rsync -av public/images/uploads/resources/ ~/www/theresettrials.com/public_html/images/uploads/resources/
```

## 4) Exit

```bash
exit
```

## 5) Quick verification

1. Open the live site in an incognito window.
2. Hard refresh the changed pages.
3. If anything looks stale, run this on the server and refresh again:

```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

If `SESSION_DRIVER` uses `redis` or `memcached`, avoid `cache:clear` during live traffic because it can log users out.

If older post cover images are still broken from the previous split-path bug, run this one-time repair command on the server:

```bash
mkdir -p ~/www/theresettrials.com/public_html/images/uploads/posts
cp -n ~/www/theresettrials.com/laravel-app/public_html/images/uploads/posts/* ~/www/theresettrials.com/public_html/images/uploads/posts/ 2>/dev/null || true
cp -n ~/www/theresettrials.com/laravel-app/public/images/uploads/posts/* ~/www/theresettrials.com/public_html/images/uploads/posts/ 2>/dev/null || true
```

## Known gotchas

- Use this exact key path format in PowerShell: `$env:USERPROFILE\.ssh\lena_new`
- Do not use this broken pattern: `$env:USERPROFILE.ssh\...`
- SiteGround app path used in this project: `~/www/theresettrials.com/laravel-app`
- Live public web root used in this project: `~/www/theresettrials.com/public_html`
- If DB ever looks "reset", immediately check runtime config:

```bash
php artisan tinker --execute="echo 'DB: '.config('database.default').PHP_EOL; echo 'DB_DATABASE: '.config('database.connections.'.config('database.default').'.database').PHP_EOL;"
```
