<x-layouts.layout>
    <x-slot:title>Employee Dashboard</x-slot:title>
    <x-slot:header>
        <span>Dashboard</span>
    </x-slot:header>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-leave-credits-container>
            <x-leave-credit-card 
                type="Vacation Leave" 
                balance="15" 
                icon="fas fa-umbrella-beach" 
                bgColor="blue" 
            />
            
            <x-leave-credit-card 
                type="Sick Leave" 
                balance="12" 
                icon="fas fa-thermometer-half" 
                bgColor="green" 
            />
        </x-leave-credits-container>
        
        <x-quick-leave-request />
    </div>
    
    <x-recent-leave-history />
</x-layouts.layout>
