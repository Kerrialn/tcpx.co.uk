# TCPX LLP

### stack
- symfony
- asset mapper
- static site using [stenope/stenope](https://packagist.org/packages/stenope/stenope):

### deployment
- Deployed to Github Pages

1. `php bin/console tailwind:build --minify`
2. `php bin/console asset-map:compile`
3. `bin/console -e prod stenope:build ./static --host=tcpx.co.uk --scheme=https`
- Can not run on dev without a server up & don't drag/drop into browser from ./static (paths will be incorrect)

