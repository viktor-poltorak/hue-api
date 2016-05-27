import requests, json


class Hue:
    url = False

    def __init__(self, bridgeIp, userName, ligthNum):
        self.url = "http://" + bridgeIp + "/api/" + userName + "/lights/" + str(ligthNum) + "/state"

    def sendRequest(self, data={}, method="GET"):
        method = method.lower();
        print(data);

        if method == 'get':
            response = requests.get(self.url, data)

        if method == 'post':
            response = requests.post(self.url, data)

        if method == 'put':
            response = requests.put(self.url, data=json.dumps(data))

        if method == 'delete':
            response = requests.delete(self.url)

        return self.handleResponse(response)

    def handleResponse(self, response):
        jsonResponse = json.loads(response.text)
        print(jsonResponse)
        if response.status_code == 200:
            return jsonResponse
        else:
            return response

    def turnOn(self, hue, bri=255, sat=255):
        return self.sendRequest({"on": True, "hue": hue, "bri": bri, "sat": sat}, 'PUT')

    def setSate(self, hue, bri=255, sat=255):
        return self.sendRequest({"hue": hue, "bri": bri, "sat": sat}, 'PUT')

    def turnOff(self):
        return self.sendRequest({"on": False}, 'put')
