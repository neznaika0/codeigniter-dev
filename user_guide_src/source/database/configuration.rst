######################
Database Configuration
######################

.. contents::
    :local:
    :depth: 3

.. note::
    See :ref:`requirements-supported-databases` for currently supported database drivers.

***********
Config File
***********

CodeIgniter has a config file that lets you store your database
connection values (username, password, database name, etc.). The config
file is located at **app/Config/Database.php**. You can also set
database connection values in the **.env** file. See below for more details.

Setting Default Database
========================

The config settings are stored in a class property that is an array with this
prototype:

.. literalinclude:: configuration/001.php

The name of the class property is the connection name, and can be used
while connecting to specify a group name.

.. note:: The default location of the SQLite3 database is the **writable** folder.
    If you want to change the location, you must set the full path to the new folder (e.g., 'database' => WRITEPATH . 'db/database_name.db').

DSN
---

Some database drivers (such as Postgre, OCI8) requires a full DSN (Data Source Name) string to connect.
But if you do not specify a DSN string for a driver that requires it, CodeIgniter
will try to build it with the rest of the provided settings.

If you specify a DSN, you should use the ``'DSN'`` configuration setting, as if
you're using the driver's underlying native PHP extension, like this:

.. literalinclude:: configuration/002.php
    :lines: 11-15

DSN in Universal Manner
^^^^^^^^^^^^^^^^^^^^^^^

You can also set a DSN in universal manner (URL like). In that case DSNs must have this prototype:

.. literalinclude:: configuration/003.php
    :lines: 11-14

To override default config values when connecting with a universal version of the DSN string,
add the config variables as a query string:

.. literalinclude:: configuration/004.php
    :lines: 11-15

.. literalinclude:: configuration/010.php
    :lines: 11-15

.. note:: If you provide a DSN string and it is missing some valid settings (e.g., the
    database character set), which are present in the rest of the configuration
    fields, CodeIgniter will append them.

Failovers
---------

You can also specify failovers for the situation when the main connection cannot connect for some reason.
These failovers can be specified by setting the failover for a connection like this:

.. literalinclude:: configuration/005.php

You can specify as many failovers as you like.

Setting Multiple Databases
==========================

You may optionally store multiple sets of connection
values. If, for example, you run multiple environments (development,
production, test, etc.) under a single installation, you can set up a
connection group for each, then switch between groups as needed. For
example, to set up a "test" environment you would do this:

.. literalinclude:: configuration/006.php

Then, to globally tell the system to use that group you would set this
variable located in the config file:

.. literalinclude:: configuration/007.php

.. note:: The name ``test`` is arbitrary. It can be anything you want. By
    default we've used the word ``default`` for the primary connection,
    but it too can be renamed to something more relevant to your project.

Changing Databases Automatically
================================

You could modify the config file to detect the environment and automatically
update the ``defaultGroup`` value to the correct one by adding the required logic
within the class' constructor:

.. literalinclude:: configuration/008.php

.. _database-config-with-env-file:

**************************
Configuring with .env File
**************************

You can also save your configuration values within a **.env** file with the current server's
database settings. You only need to enter the values that change from what is in the
default group's configuration settings. The values should follow this format, where
``default`` is the group name::

    database.default.username = 'root';
    database.default.password = '';
    database.default.database = 'ci4';

But you cannot add a new property by setting environment variables, nor change a
scalar value to an array. See :ref:`env-var-replacements-for-data` for details.

So if you want to use SSL with MySQL, you need a hack. For example, set the array
values as a JSON string in your **.env** file:

::

    database.default.encrypt = {"ssl_verify":true,"ssl_ca":"/var/www/html/BaltimoreCyberTrustRoot.crt.pem"}

and decode it in the constructor in the Config class:

.. literalinclude:: configuration/009.php

.. _database-config-explanation-of-values:

*********************
Description of Values
*********************

================ ===========================================================================================================
 Config Name     Description
================ ===========================================================================================================
**DSN**          The DSN connect string (an all-in-one configuration sequence).
**hostname**     The hostname of your database server. Often this is 'localhost'.
**username**     The username used to connect to the database. (``SQLite3`` does not use this.)
**password**     The password used to connect to the database. (``SQLite3`` does not use this.)
**database**     The name of the database you want to connect to.

                 .. note:: CodeIgniter doesn't support dots (``.``) in the table and column names.
                    Since v4.5.0, database names with dots are supported.
**DBDriver**     The database driver name. The case must match the driver name.
                 You can set a fully qualified classname to use your custom driver.
                 Supported drivers: ``MySQLi``, ``Postgre``, ``SQLite3``, ``SQLSRV``, and ``OCI8``.
**DBPrefix**     An optional table prefix which will be added to the table name when running
                 :doc:`Query Builder <query_builder>` queries. This permits multiple CodeIgniter
                 installations to share one database.
**pConnect**     true/false (boolean) - Whether to use a persistent connection.
**DBDebug**      true/false (boolean) - Whether to throw exceptions when database errors occur.
**charset**      The character set used in communicating with the database.
**DBCollat**     (``MySQLi`` only) The character collation used in communicating with the database.
**swapPre**      A default table prefix that should be swapped with ``DBPrefix``. This is useful for distributed
                 applications where you might run manually written queries, and need the prefix to still be
                 customizable by the end user.
**schema**       (``Postgre`` and ``SQLSRV`` only) The database schema, default value varies by driver.
**encrypt**      (``MySQLi`` and ``SQLSRV`` only) Whether to use an encrypted connection.
                 See :ref:`MySQLi encrypt <mysqli-encrypt>` for ``MySQLi`` settings.
                 ``SQLSRV`` driver accepts true/false.
**compress**     (``MySQLi`` only) Whether to use client compression.
**strictOn**     (``MySQLi`` only) true/false (boolean) - Whether to force "Strict Mode" connections, good for ensuring
                 strict SQL while developing an application.
**port**         The database port number - Empty string ``''`` for default port (or dynamic port with ``SQLSRV``).
**foreignKeys**  (``SQLite3`` only) true/false (boolean) - Whether to enable Foreign Key constraint.

                 .. important:: SQLite3 Foreign Key constraint is disabled by default.
                     See `SQLite documentation <https://www.sqlite.org/pragma.html#pragma_foreign_keys>`_.
                     To enforce Foreign Key constraint, set this config item to true.
**busyTimeout**  (``SQLite3`` only) milliseconds (int) - Sleeps for a specified amount of time when a table is locked.
**synchronous**  (``SQLite3`` only) flag (int) - How strict SQLite will be at flushing to disk during transactions.
                 Use `null` to stay with the default setting. This can be used since v4.6.0.
**numberNative** (``MySQLi`` only) true/false (boolean) - Whether to enable MYSQLI_OPT_INT_AND_FLOAT_NATIVE.
**foundRows**    (``MySQLi`` only) true/false (boolean) - Whether to enable MYSQLI_CLIENT_FOUND_ROWS.
**dateFormat**   The default date/time formats as PHP's `DateTime format`_.
                 * ``date``        - date format
                 * ``datetime``    - date and time format
                 * ``datetime-ms`` - date and time with millisecond format
                 * ``datetime-us`` - date and time with microsecond format
                 * ``time``        - time format
                 This can be used since v4.5.0, and you can get the value, e.g., ``$db->dateFormat['datetime']``.
                 Currently, the database drivers do not use these values directly,
                 but :ref:`Model <model-saving-dates>` uses them.
================ ===========================================================================================================

.. _DateTime format: https://www.php.net/manual/en/datetime.format.php

.. note:: Depending on what database driver you are using (``MySQLi``, ``Postgre``,
    etc.) not all values will be needed. For example, when using ``SQLite3`` you
    will not need to supply a username or password, and the database name
    will be the path to your database file.

MySQLi
======

hostname
--------

Configuring a Socket Connection
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

To connect to a MySQL server over a filesystem socket, the path to the socket should be specified in
the ``'hostname'`` setting. CodeIgniter's MySQLi driver will notice this and configure the
connection properly.

.. literalinclude:: configuration/011.php
    :lines: 11-18

.. _mysqli-encrypt:

encrypt
-------

MySQLi driver accepts an array with the following options:

* ``ssl_key``    - Path to the private key file
* ``ssl_cert``   - Path to the public key certificate file
* ``ssl_ca``     - Path to the certificate authority file
* ``ssl_capath`` - Path to a directory containing trusted CA certificates in PEM format
* ``ssl_cipher`` - List of *allowed* ciphers to be used for the encryption, separated by colons (``:``)
* ``ssl_verify`` - true/false (boolean) - Whether to verify the server certificate or not
