Vagrant.configure("2") do |config|
    config.vm.box = "trusty64"
    config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"

    config.ssh.forward_agent = true

    config.vm.define "statsd_librato_one" do |statsd_librato_one|
        statsd_librato_one.vm.network :forwarded_port, guest: 22, host: 2101
        statsd_librato_one.vm.hostname = "StatsdLibratoOne"
        statsd_librato_one.vm.network :private_network, ip: "192.168.50.201"
        statsd_librato_one.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbook_statsd_librato.yml"
            ansible.extra_vars = "ansible/config/statsd_librato_one.json"
            ansible.inventory_path = "ansible/hosts"
            ansible.host_key_checking = false
            #ansible.verbose = "v"
            ansible.limit = "statsd_librato_one"
        end
    end

    config.vm.define "statsd_librato_two" do |statsd_librato_two|
        statsd_librato_two.vm.network :forwarded_port, guest: 22, host: 2102
        statsd_librato_two.vm.hostname = "StatsdLibratoTwo"
        statsd_librato_two.vm.network :private_network, ip: "192.168.50.202"
        statsd_librato_two.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbook_statsd_librato.yml"
            ansible.extra_vars = "ansible/config/statsd_librato_two.json"
            ansible.inventory_path = "ansible/hosts"
            ansible.host_key_checking = false
            #ansible.verbose = "v"
            ansible.limit = "statsd_librato_two"
        end
    end

    config.vm.define "sandbox" do |sandbox|
        sandbox.vm.network :forwarded_port, guest: 22, host: 2103
        sandbox.vm.hostname = "sandbox"
        config.vm.synced_folder "sandbox", "/vagrant", :nfs => true
        sandbox.vm.network :private_network, ip: "192.168.50.203"
        sandbox.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbook_sandbox.yml"
            ansible.inventory_path = "ansible/hosts"
            ansible.host_key_checking = false
            #ansible.verbose = "v"
            ansible.limit = "sandbox"
        end
    end

    config.vm.define "collectd_statsd" do |collectd_statsd|
        collectd_statsd.vm.network :forwarded_port, guest: 22, host: 2104
        collectd_statsd.vm.hostname = "CollectdStatsd"
        collectd_statsd.vm.network :private_network, ip: "192.168.50.204"
        collectd_statsd.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbook_collectd_statsd.yml"
            ansible.inventory_path = "ansible/hosts"
            ansible.host_key_checking = false
            #ansible.verbose = "v"
            ansible.limit = "collectd_statsd"
        end
    end

    config.vm.define "logs" do |logs|
        logs.vm.network :forwarded_port, guest: 22, host: 2105
        logs.vm.hostname = "Logs"
        logs.vm.network :private_network, ip: "192.168.50.205"
        logs.vm.provision "ansible" do |ansible|
            ansible.playbook = "ansible/playbook_logs.yml"
            ansible.inventory_path = "ansible/hosts"
            ansible.host_key_checking = false
            #ansible.verbose = "v"
            ansible.limit = "logs"
        end
    end
end