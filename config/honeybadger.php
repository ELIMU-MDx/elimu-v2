<?php

use Honeybadger\HoneybadgerLaravel\Breadcrumbs;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\CacheHit;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\CacheMiss;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\DatabaseQueryExecuted;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\DatabaseTransactionCommitted;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\DatabaseTransactionRolledBack;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\DatabaseTransactionStarted;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\JobQueued;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\MailSending;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\MailSent;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\MessageLogged;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\NotificationFailed;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\NotificationSending;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\NotificationSent;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\RedisCommandExecuted;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\RouteMatched;
use Honeybadger\HoneybadgerLaravel\Breadcrumbs\ViewRendered;

return [
    /**
     * Your project's Honeybadger API key. Get this from the project settings on your Honeybadger dashboard.
     */
    'api_key' => env('HONEYBADGER_API_KEY'),

    /**
     * The application environment.
     */
    'environment_name' => env('APP_ENV'),

    /**
     * To disable exception reporting, set this to false.
     */
    'report_data' => ! in_array(env('APP_ENV'), ['local', 'testing']),

    /**
     * When reporting an exception, we'll automatically include relevant environment variables.
     * See the Environment Whitelist (https://docs.honeybadger.io/lib/php/reference/configuration.html#environment-whitelist) for details.
     * Use this section to filter or include env variables.
     */
    'environment' => [
        /**
         * List of environment variables that should be filtered out when sending a report to Honeybadger.
         */
        'filter' => [
            // "QUERY_STRING",
        ],

        /**
         * List of environment variables that should be included when sending a report to Honeybadger.
         */
        'include' => [
            // "APP_DEBUG"
        ],
    ],

    /**
     * We include details of the request when reporting an exception. Use this section to configure this.
     */
    'request' => [
        /**
         * Fields in the request body that should be filtered out.
         * By default, we filter out any fields named similarly to "password" or "token", but you can add more.
         */
        'filter' => [
            // "credit_card_number",
        ],
    ],

    /**
     * The current version of your application. Use this to easily tie errors to specific releases or commits.
     * If you'd like to automatically use the Git commit hash as the version, set this to:
     *   'version' => trim(exec('git log --pretty="%h" -n1 HEAD')).
     */
    'version' => env('APP_VERSION'),

    /**
     * The hostname of the machine the app is running on.
     */
    'hostname' => gethostname(),

    /**
     * The root directory of your project.
     */
    'project_root' => base_path(),

    /**
     * Older PHP functions use the Error class, while modern PHP mostly uses Exception.
     * Specify if you'd like Honeybadger to report both types of errors.
     */
    'handlers' => [
        'exception' => true,
        'error' => true,
    ],

    /**
     * Customise the Guzzle client the Honeybadger SDK uses internally.
     * See https://docs.guzzlephp.org/en/stable/request-options.html for a description of each item,.
     */
    'client' => [
        'timeout' => 15,
        'proxy' => [],
        'verify' => env('HONEYBADGER_VERIFY_SSL', true),
    ],

    /**
     * Exception classes that should not be reported to Honeybadger.
     */
    'excluded_exceptions' => [],

    'breadcrumbs' => [
        /**
         * Enable recording of breadcrumbs (application events).
         * Setting this to false will disable automatic breadcrumbs and the addBreadcrumb() function.
         */
        'enabled' => true,

        /**
         * Events which should automatically be recorded by the Honeybadger client.
         * Note that to track redis events, you need to call `Redis::enableEvents()` in your app.
         */
        'automatic' => [
            DatabaseQueryExecuted::class,
            DatabaseTransactionStarted::class,
            DatabaseTransactionCommitted::class,
            DatabaseTransactionRolledBack::class,
            CacheHit::class,
            CacheMiss::class,
            JobQueued::class,
            MailSending::class,
            MailSent::class,
            MessageLogged::class,
            NotificationSending::class,
            NotificationSent::class,
            NotificationFailed::class,
            RedisCommandExecuted::class,
            RouteMatched::class,
            ViewRendered::class,
        ],
    ],
];
