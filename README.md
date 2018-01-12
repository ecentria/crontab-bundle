Ecentria Crontab Bundle
=====================

[Crontab](http://crontab.org/) Functionality of scheduling cron jobs.<br><br>
The software utility cron is a time-based job scheduler in Unix-like computer operating systems.<br>
People who set up and maintain software environments use cron to schedule jobs to run periodically<br>
at fixed times, dates, or intervals.
<br><br>
This bundle provides a simple possibility to control crontab on application level.
Provides a possibility to auto-setup cron jobs required for project based on configuration file. 

<br>

# Table of Contents
1. [Installation](#install)
2. [Configuration](#configuration)
3. [Usage](#usage)
4. [License](#license)
5. [Important Notice](#important-notice)

<br>

<a name="install"></a>
## Installation

console
```bash
$ composer require ecentria/crontab-bundle
```

php
```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Ecentria\Bundle\CrontabBundle\EcentriaCrontabBundle()
        );
        
        // ...
    }
}
```

<br>

<a name="configuration"></a>
## Configuration
Bundle supports defining configuration by using regular expressions.
Sequence of consumers collecting:
- Exact match will go first, if match is found collecting is finished.
- Searching for first match by regular expression

config.yml
```yaml
ecentria_crontab:
    path: 'home/sites/shared/crontab' 
    user: 'project_specific_user'
    mailto: 'your.email@address.com'
    jobs:
        backend.example.com:
            -
                description: 'Backend'
                frequency:   '* * * * *'
                command:     'php /home/sites/current/bin/console backend'
                parameters:  '--env=prod --no-debug'
        node-\d+.example.com:
            -
                description: 'Node job 1'
                frequency:   '* * * * *'
                command:     'php /home/sites/current/bin/console node'
                parameters:  '--execute=foo --env=prod --no-debug'
            -
                description: 'Node job 2'
                frequency:   '* * * * *'
                command:     'php /home/sites/current/bin/console node'
                parameters:  '--execute=bar --env=prod --no-debug'
```

<br>

<a name="usage"></a>
## Usage

Setup your crontab after deployment

```bash
php bin/console ecentria:crontab:install --env=prod --no-debug
```

After command finished it's job you can check crontab with:
```bash
crontab -l
```

And if everything is correct you should see

_node-1.example.com_
```bash
# This file has been generated dynamically
# All manual changes will be erased soon

MAILTO="your.email@address.com"

# Node job 1
* * * * * php /home/sites/current/bin/console node --execute=foo --env=prod --no-debug

# Node job 2
* * * * * php /home/sites/current/bin/console node --execute=bar --env=prod --no-debug

```

<br>

<a name="license"></a>
## License
```
The MIT License

Copyright (c) 1999-2018 Ecentria Group http://www.ecentria.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```

<a name="important-notice"></a>
## Important Notice
Ecentria Crontab Bundle schedules jobs using [Crontab](https://en.wikipedia.org/wiki/Cron).
A best practice for two or more applications that run on the same node is to assign a different user per application.

The best practice mentioned above prevents different applications overwriting each other's configurations.
One user has only one cron table file, normally stored on /var/spool/cron/ 