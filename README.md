# TCPX LLP

- static site using [stenope/stenope](https://packagist.org/packages/stenope/stenope):
- generate using: `bin/console -e prod stenope:build ./static --host=tcpx.co.uk --scheme=https`
- Can not run on dev without a server up & don't drag/drop into browser from ./static (paths will be incorrect)
- deployed to github pages

### stack
- symfony
- asset mapper