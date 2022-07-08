require 'securerandom'

class BitzlatoClient
  WrongResponse = Class.new StandardError

  def initialize(home_url: , key: , logger: false, email: nil, uid: nil)
    raise ArgumentError, 'email or uid must be presented' if uid.nil? && email.nil?
    @email = email
    @uid = uid
    @jwk = JWT::JWK.import key
    @home_url = home_url
    @logger = logger
  end

  def get(path, params = {})
    parse_response connection.get path, params
  end

  def post(path, params = {})
    parse_response connection.post path, params.to_json
  end

  private

  def parse_response(response)
    raise WrongResponse, "Wrong response status (#{response.status})" unless response.success?
    raise WrongResponse, "Wrong content type (#{response['content-type']})" unless response['content-type'] == 'application/json'
    JSON.parse response.body
  end

  def claims
    {
      "aud": "usr",
      "iat": Time.now.to_i,
      "jti": SecureRandom.hex(10)
    }.tap { |c|
      c['uid'] = @uid unless @uid.nil?
      c['email'] = @email unless @email.nil?
    }
  end

  def connection
    @connection ||= Faraday.new url: @home_url do |c|
      c.use Faraday::Response::Logger if @logger
      c.headers = {
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
      }
      c.authorization :Bearer, bearer
    end
  end

  def bearer
    JWT.encode claims, @jwk.keypair, 'ES256', kid: '1'
  end
end
