

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

# Windows 7

## Install Node.js
```
http://nodejs.org/download/

## Install Grunt
```open shell with administrative rights
npm install -g grunt-cli
```

## Install Handlebars
```shell
npm install handlebars@1.0.12 -g
```

# Conclusion (Both)

## Use Grunt to watch over changed css & js files
Change to project folder in the terminal

```shell
grunt watch
```
The shell will stay open and execute the tasks in case the specified files change.


