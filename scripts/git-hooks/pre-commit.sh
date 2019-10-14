#!/bin/bash

# Checks whether there are significant changes to the config files and whether
# the history tables have consistent schemas.

php scripts/checkSampleFiles.php && php scripts/checkHistorySchema.php
