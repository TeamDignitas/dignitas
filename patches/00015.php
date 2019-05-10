<?php

// cannot do this in a SQL patch because we cannot make assumptions about the
// DB name
DB::execute(sprintf('alter database %s charset utf8mb4',
                    Config::DB_DATABASE));
