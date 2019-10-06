# About

## Architecture

![](https://github.com/t-kuni/github-issue-2-anki-card/tree/master/docs/architecture.png)

# Build

Build container.

```
docker-compose build app
```

Install php libraries.

```
docker-compose run --rm app composer install
```

Install node packages.  
Run follow command on host OS.

```
npm install
```

# Run

```
docker-compose run --rm app
```

# Boot shell

```
docker-compose run --rm app sh
```