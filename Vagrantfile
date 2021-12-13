# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.

# X86 Host - use Virtualbox
# M1 Host - use Vmware Fusion --provider=vmware_desktop

Vagrant.configure("2") do |config|
  config.vm.define "signal"
  config.vm.box = "ubuntu/focal64"
  config.vm.box_check_update = false
  config.vm.provision :shell, path: "vagrant/bootstrap.sh"
  config.vm.network :forwarded_port, guest: 80, host: 80
  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--uart1", "0x3F8", "4"]
    vb.customize ["modifyvm", :id, "--uartmode1", "file", File::NULL]
  end
  config.vm.provider "vmware_fusion" do |v, override|
    override.vm.box = "rkrause/ubuntu-20.04-arm64"
  end
end
