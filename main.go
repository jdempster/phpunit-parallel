package main

import "github.com/alexdempster44/phpunit-parallel/cmd"

var version = "dev"

func main() {
	cmd.SetVersionInfo(version)
	cmd.Execute()
}
