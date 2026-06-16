import urllib.request
import json
import sqlite3

# Get student token? Oh wait, we don't need a token, we can just call it via local request? No, it requires Authorize!
# I need to create a JWT token. Or I can just write a C# script to query the DB to see exactly what `GetSchedule` logic returns.
