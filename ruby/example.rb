#!/usr/bin/env ruby
require 'rubygems'
require 'bundler/setup'

Bundler.require(:default)

require_relative './bitzlato_client'

key = JSON.parse(ENV.fetch('BITZLATO_API_KEY')).transform_keys(&:to_sym)

client = BitzlatoClient
  .new(home_url: ENV.fetch('BITZLATO_API_URL'), key: key, uid: ENV.fetch('BITZLATO_API_CLIENT_UID').to_i, logger: ENV.key?('BITZLATO_API_LOGGER'))

puts $0

if ARGV.length == 1
  puts client.get ARGV[0] || '/api/auth/whoami'
elsif ARGV.length == 2
  puts client.send(ARGV[0], ARGV[1])
elsif ARGV.length == 3
  puts client.send(ARGV[0], ARGV[1], JSON.parse(ARGV[2]))
else
  puts "Run example: #{$0} GET /api/auth/whoami"
end

