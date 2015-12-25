stage { [pre, post]: }
Stage[pre] -> Stage[main] -> Stage[post]

# palya-apt ####################################################################

class palya-apt {
    exec { "/usr/bin/apt-get update -y": }
}

# palya-mongodb ################################################################

class palya-mongodb {
    class { mongodb::globals: manage_package_repo => true }
    class { mongodb: fork => false }
}

# palya-postgesql ##############################################################

class palya-postgresql {
    class { postgresql::server: }

    postgresql::server::db { 'palya-test':
        user => 'palya',
        password => postgresql_password('palya', 'palya'),
    }
}

# palya-php ####################################################################

class palya-php {
    $packages = [
        php5,
        php5-intl,
        php5-mongo,
        php5-mysql,
        php5-pgsql,
        php5-xcache
    ]

    package { $packages: ensure => latest }
}

# palya-std ####################################################################

class palya-std {
    $packages = [
        curl,
        git
    ]

    package { $packages: ensure => latest }
}

class { palya-apt: stage => pre }
class { palya-mongodb: }
class { palya-postgresql: }
class { palya-php: }
class { palya-std: }
