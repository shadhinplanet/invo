name: FTP Deploy
on:
  push:
    branches: [ master ]
  pull_request:
    branches: [ master ]
  # Allows you to run this workflow manually from the Actions tab
  workflow_dispatch:

# A workflow run is made up of one or more jobs that can run sequentially or in parallel
jobs:
  web-deploy:
    name: 🎉 Deploy
    runs-on: ubuntu-latest
    steps:
    - name: 🚚 Get latest code
      uses: actions/checkout@v2

    - name: 📂 Sync files
      uses: SamKirkland/FTP-Deploy-Action@4.3.0
      with:
        server: ${{ secrets.ftp_server }}
        username: ${{ secrets.ftp_username}}
        password: ${{ secrets.ftp_password }}
        server-dir: /invo.pixcafe.xyz/

    - name: Git clone
      run: git clone https://github.com/shadhinplanet/invo.git
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    - name: Generate key
      run: php artisan key:generate



name: FTP Deploy
on:
  push:
    branches: [ master ]
  pull_request:
    branches: [ master ]
  # Allows you to run this workflow manually from the Actions tab
  workflow_dispatch:

# A workflow run is made up of one or more jobs that can run sequentially or in parallel
jobs:
   web-deploy:
    name: 🎉 Deploy
    runs-on: ubuntu-latest
    steps:
    - name: 🚚 Get latest code
      uses: actions/checkout@v2

    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"

    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

    - name: Generate key
      run: php artisan key:generate

    - name: Configure environment
      env:
        DB_CONNECTION: mysql
        DB_HOST: ${{ secrets.db_host }}
        DB_DATABASE: ${{ secrets.db_name }}
        DB_USERNAME: ${{ secrets.db_username }}
        DB_PASSWORD: ${{ secrets.db_password }}

    - name: Configure Queue
      env:
        QUEUE_CONNECTION: database

    - name: Configure Email
      env:
        MAIL_DRIVER: smtp
        MAIL_HOST: ${{ secrets.mail_host }}
        MAIL_PORT: ${{ secrets.mail_port }}
        MAIL_USERNAME: ${{ secrets.mail_username }}
        MAIL_PASSWORD: ${{ secrets.mail_password }}
        MAIL_ENCRYPTION: ${{ secrets.mail_encryption }}
        MAIL_FROM_ADDRESS: ${{ secrets.mail_from_address }}

    - name: Sync files
      uses: SamKirkland/FTP-Deploy-Action@4.3.0
      with:
        server: ${{ secrets.ftp_server }}
        username: ${{ secrets.ftp_username}}
        password: ${{ secrets.ftp_password }}
        server-dir: /invo.pixcafe.xyz/
