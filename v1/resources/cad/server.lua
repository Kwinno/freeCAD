local RandomEndpoints = {
	['identities'] = { Message = 'people are currently signed up to our CAD!' }
}

AddEventHandler('playerConnecting', function()
    if not string.find(GetPlayerIdentifiers(source)[1], "steam:") then
	deferrals.defer()
	deferrals.done('Steam is required to play on this server, please relaunch your game with steam open.')
    end
end)

Citizen.CreateThread(function()
    while true do
	for a = 1, #RandomEndpoints do
		PerformHttpRequest(Config['Website'] .. '/api?endpoint=' .. RandomEndpoints[a], function(statusCode, response, headers)
			if response then
				local Information = json.decode(response)
				local Amount = 0
				for b = 1, #Information
					Amount = Amount + 1
				end
				TriggerClientEvent('chatMessage', -1, '', {255, 255, 255}, Amount .. ' ' .. Message)
			end      
		end)
		Citizen.Wait(300000)
	end
	Citizen.Wait(300000)
    end
end)

function urlencode(str)
    if (str) then
	str = string.gsub (str, "\n", "\r\n")
	str = string.gsub (str, "([^%w ])",
		function (c) return string.format ("%%%02X", string.byte(c)) end)
	str = string.gsub (str, " ", "+")
   end
   return str    
end
