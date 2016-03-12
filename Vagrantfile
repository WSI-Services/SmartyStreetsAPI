require 'json'
require 'yaml'

VAGRANTFILE_API_VERSION = "2"
confDir = $confDir ||= File.expand_path("vendor/laravel/homestead", File.dirname(__FILE__) )

homesteadYamlPath = "utilities/vagrant/Homestead.yaml"
homesteadJsonPath = "utilities/vagrant/Homestead.json"
afterScriptPath = "utilities/vagrant/after.sh"
aliasesPath = "utilities/vagrant/aliases"

require File.expand_path(confDir + '/scripts/homestead.rb')

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box_version = "= 0.3.3"
    config.vm.box_check_update = false

    if File.exists? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "~/.bash_aliases"
    end

    if File.exists? homesteadYamlPath then
        Homestead.configure(config, YAML::load(File.read(homesteadYamlPath)))
    elsif File.exists? homesteadJsonPath then
        Homestead.configure(config, JSON.parse(File.read(homesteadJsonPath)))
    end

    if File.exists? afterScriptPath then
        config.vm.provision "shell", path: afterScriptPath
    end
end
