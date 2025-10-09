<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discord Quests Replica</title>
  <link href="../src/output.css" rel="stylesheet" />
  <!-- ✅ Google Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body class="bg-[#1e1f22] text-white min-h-screen flex">

  <!-- Sidebar -->
  <aside class="w-60 bg-[#232428] flex flex-col p-3 border-r border-gray-800">
    <h2 class="text-lg font-semibold px-2 mb-4">Discover</h2>
    <nav class="flex flex-col gap-1">
      <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
        <span class="material-icons-outlined text-xl text-gray-300">sports_esports</span>
        <span>Apps</span>
      </a>
      <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
        <span class="material-icons-outlined text-xl text-gray-300">groups</span>
        <span>Servers</span>
      </a>
      <a href="#" class="flex items-center gap-3 px-3 py-2 bg-[#3c3f44] rounded-md">
        <span class="material-icons-outlined text-xl text-gray-300">emoji_events</span>
        <span>Quests</span>
      </a>
    </nav>

    <div class="mt-auto pt-4 border-t border-gray-700">
      <div class="flex items-center gap-3 px-3 py-2">
        <div class="w-8 h-8 rounded-full bg-green-600"></div>
        <div>
          <p class="text-sm font-medium">joshua padilla</p>
          <p class="text-xs text-gray-400">Online</p>
        </div>
        <span class="material-icons-outlined text-gray-400 text-xl cursor-pointer ml-auto hover:text-white">settings</span>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 flex flex-col bg-[#1e1f22]">
    <!-- Top Tabs -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-800 bg-[#2b2d31]">
      <div class="flex items-center gap-6">
        <button class="text-white font-semibold border-b-2 border-white pb-1">All Quests</button>
        <button class="text-gray-400 hover:text-white">Claimed Quests</button>
      </div>
      <button class="bg-gray-700 text-white px-2 py-1 rounded-full text-xs flex items-center justify-center">
        <span class="material-icons-outlined text-sm">settings</span>
      </button>
    </div>

    <!-- Banner -->
    <section class="p-6 bg-gradient-to-r from-[#1e1f22] via-[#2f3150] to-[#1e1f22]">
      <div class="rounded-2xl bg-[#2b2d31] p-10 text-center shadow-lg">
        <h1 class="text-4xl font-extrabold mb-3">INTRODUCING DISCORD ORBS</h1>
        <p class="text-gray-400 mb-5">Reward your play. Earn through Quests. Spend in the Shop.</p>
        <div class="flex justify-center gap-4">
          <button class="bg-white text-black font-semibold px-4 py-2 rounded-md hover:bg-gray-200">
            Explore Orbs Exclusives
          </button>
          <button class="bg-[#5865f2] text-white font-semibold px-4 py-2 rounded-md hover:bg-[#4752c4]">
            Discord Orbs Terms
          </button>
        </div>
      </div>
    </section>

    <!-- Available Quests -->
    <section class="px-6 pb-12">
      <h2 class="text-2xl font-bold mb-4">Available Quests</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <!-- Quest Card -->
        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <img src="https://images.unsplash.com/photo-1606112219348-204d7d8b94ee?w=600" alt="Battlefield" class="w-full h-40 object-cover">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg flex items-center gap-2">
              Battlefield 6 Trailer Quest
            </h3>
            <p class="text-gray-400 text-sm">Ends 10/10 • Promoted by EA</p>
            <p class="text-sm text-gray-300 flex items-center gap-1">
              Claim 700 Discord Orbs
            </p>
            <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
              Start Video Quest
            </button>
          </div>
        </div>

        <!-- Another Quest -->
        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <img src="https://images.unsplash.com/photo-1604020935803-6c949b8f6b51?w=600" alt="Valorant" class="w-full h-40 object-cover">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg flex items-center gap-2">
              Valorant Invite Quest
            </h3>
            <p class="text-gray-400 text-sm">Ends 10/13 • Promoted by Riot Games</p>
            <p class="text-sm text-gray-300 flex items-center gap-1">
              Claim a Tactibear Flex Avatar
            </p>
            <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
              Accept Quest
            </button>
          </div>
        </div>

        <!-- More Cards -->
        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <img src="https://images.unsplash.com/photo-1633639833930-3c419b3e5253?w=600" alt="Game" class="w-full h-40 object-cover">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg flex items-center gap-2">
              King of Meat Play Quest
            </h3>
            <p class="text-gray-400 text-sm">Ends 10/14 • Promoted by Amazon Games</p>
            <p class="text-sm text-gray-300 flex items-center gap-1">
              Claim a Bombhead’s Explade
            </p>
            <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
              Accept Quest
            </button>
          </div>
        </div>

      </div>
    </section>
  </main>
</body>
</html>
