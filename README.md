# CamaloteWP - Editorial Control

This is a WordPress plugin designed for editorial control.

## Overview

This WordPress plugin provides an editorial control system. Its primary function is to create a dedicated settings page within the WordPress admin dashboard, where users can manage and configure various custom-made features and settings. This page is built using React, providing a dynamic and interactive user interface, while a REST API endpoint is registered on the PHP side to facilitate seamless data exchange between the frontend and the server.

## Features

- Editorial Control: Manages a custom editorial workflow for content.

- Settings Page: Provides a dedicated administration page built with React for managing custom features and settings.

- Localization: The plugin supports internationalization, making it translatable into different languages.

- REST API Endpoint: Registers a REST API endpoint on the PHP side to handle data and facilitate communication with the frontend.

- Developer-Friendly: The project includes a variety of features to enhance the developer experience, such as a 10up-toolkit-based development server with Hot Module Replacement (HMR) for fast refreshes, Jest for unit testing, and PHP-Scoper to prevent dependency conflicts.

## Installation

### Prerequisites

- PHP 8.2 or greater 

- Node.js 18.0.0 or greater 

### Steps

#### Download a release

- Navigate to [Releases](https://github.com/camalote-wp/cmlt-editorial-control/releases)

- Install via WordPress backend or copy into `wp-content/plugins/`.

#### From source

- Clone the repository:  `git clone https://github.com/camalote-wp/cmlt-editorial-control.git`

- Navigate to the plugin directory: `cd cmlt-editorial-control`

- Install PHP dependencies: `composer install` 

- Install Node.js dependencies: `npm install` 

- Scope the vendor dependencies: `composer scope` 

## Development

The plugin uses `10up-toolkit` for its build process and linting.

### Available Scripts

- `npm start`: Runs npm run watch to start the development server.
- `npm watch`: Starts the webpack and TypeScript watch processes.
- `npm build`: Compiles the final production assets.
- `npm format-js`: Formats JavaScript code.
- `npm lint-js`: Lints JavaScript code.
- `npm lint-style`: Lints CSS/SCSS code.
- `npm test`: Runs unit tests using Jest.
- `npm clean-dist`: Removes the dist directory.