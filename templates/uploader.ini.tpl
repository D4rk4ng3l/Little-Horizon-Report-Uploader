[download]
sourcePath = APPLICATION_PATH "/../dist"
baseName = "uploader"
archives.zip.title = "ZIP-Datei"
archives.zip.suffix = "zip"
archives.zip.mimeType = "application/zip"
archives.targz.title = "GZipped Tar"
archives.targz.suffix = "tar.gz"
archives.targz.mimeType = "application/x-compressed-tar"
archives.tarbz.title = "BZipped Tar"
archives.tarbz.suffix = "tar.bz2"
archives.tarbz.mimeType = "application/x-bzip-compressed-tar"

[menu]
nav.type = BREAK
nav.title = "Navigation"
home.type = INTERNAL
home.href = ""
home.title = "Upload"
list.type = INTERNAL
list.href = "reports"
list.title = "Berichtsliste"
stats.type = INTERNAL
stats.href = "reports/statistics"
stats.title = "Statistiken"
version.type = INTERNAL
version.href = "index/version"
version.title = "Version"
extlinks.type = BREAK
extlinks.title = "Externe Links"
horizon.type = EXTERNAL
horizon.href = "http://horitest.goetterheimat.de/"
horizon.title = "Little Horizon"
forum.type = EXTERNAL
forum.href = "http://forum.building-better-worlds.org/"
forum.title = "Horizon Forum"
wiki.type = EXTERNAL
wiki.href = "http://wiki.building-better-worlds.org/"
wiki.title = "Horizon Wiki"
galaxymag.type = EXTERNAL
galaxymag.href = "http://horitest.goetterheimat.de/homepage/newspaper/index.php"
galaxymag.title = "Horizon GalaxyMag"

[database]
host = "${config.db.host}"
user = "${config.db.user}"
pass = "${config.db.pass}"
dbName = "${config.db.name}"
tables.metadata = "${config.db.tables.reports}"
tables.stats = "${config.db.tables.statistics}"