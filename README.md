# ArgoBot [![Bot](https://img.shields.io/badge/Telegram-%40PArgoBot-blue.svg)][Bot] [![Portfolio](https://img.shields.io/badge/Portflio-%40MyPersonalPortfolio-green.svg)][Portfolio] ![Status](https://img.shields.io/badge/Status-Developement-red.svg)
A simple italian Telegram bot to see your grades, homework and everything else of the Argo electronic school register software.

## Features
- [x] Automatic **session saving**
- [ ] View **your grades** by the bot.
- [ ] View **your homeworks** by the bot.
- [ ] View your **disciplinary notes** by the bot.
- [ ] View the **resume** of the **today's journey**.
- [ ] Get a notify if there's an **upcoming test** or everything else.

## Installation
    
- Install the dependencies with your package manager (click [here](https://en.m.wikipedia.org/wiki/List_of_software_package_management_systems) for a list) , skip if you already have them.

      $ sudo apt-get install git
      $ sudo apt-get install php7.0 php7.0-curl apache2

- Install the bot files, if you already installed dependencies start from here.
    
      $ git clone https://github.com/iDoppioclick/ArgoBot
      $ mv ArgoBot/ /var/www/html/
      $ sudo chown -R www-data:www-data /var/www/html/ArgoBot
    
- Then, edit the ```_config.php``` file with the requested values, like the API Token (Generated by [BotFather](https://t.me/BotFather)), the database name, user and password.

- Visit https://api.telegram.org/botYOURTOKEN/setWebhook?url=https://yourserverip/ArgoBot/index.php

- Send ```/start``` to your bot, it should work.

## License
See ```LICENSE``` file.

## Support
Need help? Contact [me](https://t.me/iDoppioclick) on Telegram, i will help you setting up everything!

## Acknowledgments
[Cristian Livella](https://cristianlivella.com) for his fantastic ArgoAPI.
[ale183](https://github.com/ale183) for his tips.

<!-- URLS -->
[Bot]: https://t.me/PArgoBot
[Portfolio]: https://t.me/MyPersonalPortfolio
