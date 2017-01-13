# xcore
application.ini
[common]
application.directory = APPLICATION_PATH  "/application"
application.dispatcher.catchException = TRUE
application.library = APPLICATION_PATH  "/application/library"
application.library.namespace = "Local"
application.modules = "Index,Admin"
application.view.ext = "html"
;layout配置
layout.open = 1
layout.path = APPLICATION_PATH  "/application/layout"

;数据库配置信息
database.driver     = "mysql"
database.read.0.host = "127.0.0.1"
database.write.0.host = "127.0.0.1"
database.database   = "yaf"
database.username   = "root"
database.password   = "root"
database.port       = 3306
database.charset    = utf8
database.collation  = utf8_unicode_ci
database.prefix     = ""

;缓存配置信息
cache.default = "file"
cache.prefix = "laravel"
;file
cache.stores.file.driver = "file"
cache.stores.file.path = "/var/www/cache"
;array
cache.stores.array.driver = "array"
;redis
cache.stores.redis.driver = "redis"
cache.stores.redis.connection = "default"
;database
cache.stores.database.driver = "database"
cache.stores.database.table = "caches"
cache.stores.database.connection = ""

;key生成规则 return 'base64:'.base64_encode(random_bytes(16/32))
encryption.key = 'base64:JpHTZVL0v3PUTyngDrxavbQh9N9bRjNh7JTQXedbeJI='
encryption.cipher = 'AES-256-CBC'

;redis配置
redis.cluster = "false"
redis.default.host = "127.0.0.1"
redis.default.password = "123456"
redis.default.port = "6379"
redis.default.database = "0"

;Filesystem配置
filesystem.default = "local"
filesystem.cloud = "s3"
filesystem.disks.local.driver = "local"
filesystem.disks.disks.local.root = "/var/www/cache/app"
filesystem.disks.public.driver = "local"
filesystem.disks.public.root = "/var/www/cache/app/public"
filesystem.disks.public.visibility = "public"
filesystem.disks.s3.driver = "s3"
filesystem.disks.s3.key = "your-key"
filesystem.disks.s3.secret = "your-secret"
filesystem.disks.s3.region = "your-region"
filesystem.disks.s3.bucket = "your-bucket"

;Session配置
session.driver = "database"
session.lifetime = "1"
session.expire_on_close = "false"
session.encrypt = false
session.files = "/var/www/cache/session"
session.connection = "default"
session.table = "sessions"
session.store = ""
session.lottery = "[2,100]"
session.cookie = "laravel_session"
session.path = "/tmp"
session.domain = "frame.hutong.com"
session.secure = "false"
session.http_only = "true"
[product : common]
