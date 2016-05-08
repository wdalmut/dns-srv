autocmd BufWritePost * silent exec ':!ctags -a --languages=php %'

let g:phpunit_cmd = "vendor/bin/phpunit"
let g:phpunit_args = ""
let g:relatedtest_php_src="src/"
let g:relatedtest_php_tests="tests/"

