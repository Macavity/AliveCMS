
# General

Um lokal arbeiten zu können lade dir den Dev-Branch herunter und zweige einen eigenen Branch davon ab. Von mir oder einem der andere bekommst du eine database.php die in /application/config/ gehört und nicht im Mercurial erfasst werden sollte. Der dort enthaltene MySQL-User hat auf die Realm- und Charakterdatenbank nur Lesezugriff, dennoch geh damit umsichtig um.

Du kannst bei Downloads auf bitbucket.org einen Dump der data_portal-Datenbank finden den du benutzen kannst um eine lokale Version aufzusetzen.

Wenn du eine komplette Offline-Version willst musst du die database.php auf deinen lokalen MySQL richten und dort eine trinity_realm-Datenbank bereitstellen. Ebenfalls brauchst du eine world- und character-Datenbank mit Daten damit alles funktioniert.


# Mac / Unix

## Install Homebrew if necessary
```shell
/usr/bin/ruby -e "$(curl -fsSL https://raw.github.com/mxcl/homebrew/go)"
```

## Install Node.js via Homebrew
```shell
brew install node
curl https://npmjs.org/install.sh | sh
```

## Install Grunt via Node Package Manager
```shell
npm install grunt -g
```

## Install Handlebars via Node Package Manager
```shell
npm install handlebars@1.0.12 -g
```

## Install Ruby (wenn nötig)
http://www.rubyinstaller.org/

## Install Sass
```
sudo gem install sass
```

# Windows 7

## Install Node.js
```
http://nodejs.org/download/
```

## Install Grunt
```open shell with administrative rights
npm install -g grunt-cli
```

## Install Handlebars
```shell
npm install handlebars@1.0.12 -g
```

## Install Ruby (wenn nötig)
http://www.rubyinstaller.org/


# Conclusion (Both)

## Use Grunt to watch over changed css & js files
Change to project folder in the terminal

```shell
grunt watch
```
The shell will stay open and execute the tasks in case the specified files change.


