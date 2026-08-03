# FizzBuzz API

REST API implementing a generalized FizzBuzz, built with Symfony 7.4 and API Platform 4, including a request statistics endpoint.

## Table of contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Running the project](#running-the-project)
- [Endpoints](#endpoints)
- [Testing](#testing)
- [Code quality](#code-quality)
- [Architecture](#architecture)
- [Production mode](#production-mode)

## Requirements

- Docker
- Docker Compose

No local PHP, Composer, or PostgreSQL installation is required — everything runs inside Docker.

## Installation

```bash
git clone git@github.com:full-protected/fizzbuzz-api.git
cd fizzbuzz-api
make setup
```

`make setup` builds the images, starts the containers, installs PHP dependencies, and runs database migrations for both the dev and test databases.

## Running the project

Once `make setup` has completed, the API is available at:

- API entrypoint / Swagger UI: http://localhost:8080/api
- Healthcheck: http://localhost:8080/health

Useful commands:

```bash
make up        # start the containers
make down      # stop the containers
make shell     # open a shell inside the PHP container
make logs      # follow container logs
```

## Endpoints

### GET /api/fizzbuzz

Returns a list of strings from 1 to `limit`, replacing multiples of `int1` by `str1`, multiples of `int2` by `str2`, and multiples of both by `str1str2`.

| Parameter | Type   | Constraint                  |
|-----------|--------|------------------------------|
| int1      | int    | positive                    |
| int2      | int    | positive                    |
| limit     | int    |  positive, <= 100000         |
| str1      | string | not blank, max 100 chars     |
| str2      | string | not blank, max 100 chars     |

Example:

```bash
curl "http://localhost:8080/api/fizzbuzz?int1=3&int2=5&limit=16&str1=fizz&str2=buzz"
```

```json
{"result":["1","2","fizz","4","buzz","fizz","7","8","fizz","buzz","11","fizz","13","14","fizzbuzz","16"]}
```

### GET /api/statistics

Returns the parameters of the most frequently requested FizzBuzz call, along with its number of hits. Returns `404` if no request has been made yet.

```bash
curl "http://localhost:8080/api/statistics"
```

```json
{"int1":3,"int2":5,"limit":16,"str1":"fizz","str2":"buzz","hits":3}
```

### GET /health

Simple liveness check.

```bash
curl "http://localhost:8080/health"
```

```json
{"status":"ok"}
```

## Testing

```bash
make test
```

Runs the full PHPUnit suite (unit tests for the FizzBuzz generator, functional tests for both API endpoints) against a dedicated `fizzbuzz_test` database, isolated from the development database.

## Code quality

```bash
make stan       # PHPStan static analysis (level 8)
make cs-check   # PHP-CS-Fixer, dry-run
make cs-fix     # PHP-CS-Fixer, apply fixes
```

## Architecture

The project follows a layered architecture, keeping business logic decoupled from HTTP and persistence concerns:

| Layer | Location | Responsibility |
|---|---|---|
| ApiResource | `src/ApiResource` | Describes the JSON shape of the response |
| State Provider | `src/State` | Bridges the HTTP request and the business services |
| Business Service | `src/Service` | Pure logic, no HTTP/Doctrine dependency |
| Repository | `src/Repository` | Persistence, only for statistics |

Request flow: **HTTP GET → ApiResource → State Provider → Business Service → (Repository, if needed) → JSON response**

Key design decisions:

- **`FizzBuzzService`** is a pure, stateless service — testable in isolation, with no knowledge of HTTP or the framework.
- **API Platform native query parameters** (`QueryParameter`) are used instead of an input DTO, since this is a simple `GET` endpoint — this keeps validation declarative and avoids unnecessary boilerplate.
- **`requestLimit`** is used as the property/column name at the entity and database level (since `limit` is a reserved SQL keyword), while the API always exposes the `limit` property, as required by the specification.
- **Statistics concurrency**: a unique database constraint on `(int1, int2, request_limit, str1, str2)` prevents duplicate rows; a `UniqueConstraintViolationException` is caught and handled gracefully in case of a race condition between two simultaneous identical requests.
- **JSON output** (not JSON-LD) is enforced on the `/api/fizzbuzz` resource to match the exact response format required by the specification, while Swagger/OpenAPI documentation remains fully available.
## Production mode

The committed `.env` file runs in `dev` mode by default, so Swagger UI and detailed error messages remain accessible for evaluation purposes.

To verify the application also behaves correctly in production mode:

```bash
make prod-check
```

To simulate a production install:

```bash
docker compose exec php composer install --no-dev --classmap-authoritative
```
