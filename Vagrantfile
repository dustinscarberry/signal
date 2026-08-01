# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

# X86 - use Virtualbox
# Apple Silicon - use Vmware Fusion

Vagrant.configure("2") do |config|
  config.vm.define "signal"
  config.vm.box = "bento/ubuntu-24.04"
  config.vm.box_check_update = false
  config.vm.provision :shell, path: "vagrant/bootstrap.sh"
  config.vm.network :forwarded_port, guest: 80, host: 80
end
