@props(['users', 'selectedUsers' => []])

{{-- 
Props:
- $users: Collection berisi semua user yang bisa dipilih.
- $selectedUsers: Array berisi ID user yang sudah terpilih (untuk form edit).
--}}

<div 
    x-data="{ 
        search: '', 
        allUsers: {{ json_encode($users) }},
        selectedUsers: {{ json_encode($selectedUsers) }},
        
        get filteredUsers() {
            if (this.search === '') {
                return this.allUsers;
            }
            return this.allUsers.filter(user => {
                return user.name.toLowerCase().includes(this.search.toLowerCase()) ||
                       user.email.toLowerCase().includes(this.search.toLowerCase());
            });
        },

        toggleUser(userId) {
            const index = this.selectedUsers.indexOf(userId);
            if (index === -1) {
                this.selectedUsers.push(userId);
            } else {
                this.selectedUsers.splice(index, 1);
            }
        }
    }"
>
    <input 
        type="text" 
        x-model="search" 
        placeholder="Search by name or email..." 
        class="block w-full border-gray-300 rounded-md shadow-sm mb-2"
    >

    <div class="border rounded-md p-2 h-60 overflow-y-auto">
        <template x-for="user in filteredUsers" :key="user.id">
            <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-100">
                <input 
                    type="checkbox"
                    :value="user.id"
                    :checked="selectedUsers.includes(user.id)"
                    @click="toggleUser(user.id)"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >
                <span>
                    <span x-text="user.name"></span>
                    <span class="text-sm text-gray-500" x-text="`(${user.email})`"></span>
                </span>
            </label>
        </template>
        <template x-if="filteredUsers.length === 0">
            <p class="text-center text-gray-500 p-4">No users found.</p>
        </template>
    </div>

    <template x-for="userId in selectedUsers">
        <input type="hidden" name="users[]" :value="userId">
    </template>
</div>