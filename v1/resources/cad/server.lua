AddEventHandler('playerConnecting', function()
   if not string.find(GetPlayerIdentifiers(source)[1], "steam:") then
      deferrals.defer()
      deferrals.done('Steam is required to play on this server, please relaunch your game with steam open.')
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
