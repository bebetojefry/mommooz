set   :application,   "MOMMOOZ"
set   :deploy_to,     "/var/www/html/mommooz_release"
set   :user,          "primal"
set   :domain,        "139.59.27.156"

set   :scm,           :git
set   :repository,    "ssh://git@github.com:bebetojefry/mommooz.git"
set   :deploy_via,    :remote_cache

role  :web,           domain
role  :app,           domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3