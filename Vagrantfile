# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  config.vm.define "signal-dev"
  config.vm.box = "ubuntu/bionic64"
  config.vm.provision :shell, path: "vagrant/bootstrap.sh"
  config.vm.network :forwarded_port, guest: 80, host: 80
  #config.vm.network :forwarded_port, guest: 443, host: 443
  #config.vm.network :private_network, ip: "192.168.3.10"
  #config.vm.hostname = "signal.marshall.edu"
  config.vm.provider "virtualbox" do |vb|
      vb.customize ["modifyvm", :id, "--uartmode1", "disconnected"]
  end
#  config.vm.synced_folder ".", "/vagrant", type: "rsync", rsync__exclude: ".git/", rsync__auto: true
end
