<br />
<div align="center">
  <p align="center"> 
    <a href="https://php.net/" target="_blank"><img src="https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg"></a>
  </p>

  <strong>
    <h2 align="center">Sitemap Crawler</h2>
  </strong>

  <p align="center">
    Small application for crawling and storing sitemaps.
  </p>

  <br>

  <p align="center">
    <a href="https://laravel.com/">
      <img src="https://www.vectorlogo.zone/logos/laravel/laravel-icon.svg" height="45" />
    </a>
  </p>
</div>
<br />

## Index

<pre>
<a href="#installation"
>> Installation ..................................................................... </a>
<a href="#usage"
>> Usage ............................................................................ </a>
<a href="#bruno"
>> Bruno ............................................................................ </a>
<a href="#linting"
>> Linting .......................................................................... </a>
</pre>

## Installation

Clone and set up the project:

```bash
git git@github.com:mindtwo/sitemap-crawler.git
just --list # Check out available tasks
just setup
# Application running at https://sitemap-crawler.ddev.site
```

After the initial setup, you may start and stop the application by running `just up` or `just down`.

## Usage

To crawl a sitemap, run the following command:

```bash
ddev artisan sitemap:crawl https://example.com/sitemap.xml
```

## Bruno

A [Bruno](https://www.usebruno.com/) collection may be found under `documentation/bruno`.

## Linting

To lint (and fix) your PHP code, run the following command:

```bash
just lint
```

Make sure your code passes before pushing, since otherwise the build will fail and your pull request
won't be merged.
