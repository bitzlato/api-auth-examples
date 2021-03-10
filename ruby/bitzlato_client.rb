require 'securerandom'

class BitzlatoClient
  def initialize(home_url: , key: , logger: false, email: nil, uid: nil)
    raise 'email or uid must be presented' if uid.nil? && email.nil?
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
    response = connection.post path do |req|
      req.body = params.to_json
    end
    parse_response response
  end

  private

  def parse_response(response)
    raise "Response status is #{response.status}" unless response.success?
    # TODO validate content type and status
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
