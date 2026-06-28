
<div class="w-full max-w-3xl mx-auto p-4">
    <div class='question'>
        <p class="text-lg">?</p>
        
        <div class="bg-gray-800 text-white p-4 rounded-md mt-2 text-sm leading-6 font-mono overflow-x-auto">For each statement, select True or False.
Note: You will receive partial credit for each correct selection.</div>
    </div>
    <div class="mt-6 space-y-2" id="sentences-container">
        
        <div class='sentence-item flex items-center justify-between p-3 border bg-gray-50 rounded-lg mb-2' data-sentence-id='0'>
            <p class='flex-grow mr-4 text-gray-800'>You can delete data by using a stored procedure.</p>
            <div class='flex-shrink-0 space-x-2'>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='true'>True</button>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='false'>False</button>
            </div>
        </div>
        <div class='sentence-item flex items-center justify-between p-3 border bg-gray-50 rounded-lg mb-2' data-sentence-id='1'>
            <p class='flex-grow mr-4 text-gray-800'>A function must have a return value.</p>
            <div class='flex-shrink-0 space-x-2'>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='true'>True</button>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='false'>False</button>
            </div>
        </div> 
        <div class='sentence-item flex items-center justify-between p-3 border bg-gray-50 rounded-lg mb-2' data-sentence-id='2'>
            <p class='flex-grow mr-4 text-gray-800'>A stored procedure must have a return value.</p>
            <div class='flex-shrink-0 space-x-2'>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='true'>True</button>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500' data-value='false'>False</button>
            </div>
        </div>
    </div>
    <div class='response border p-3 mt-6 hidden rounded-lg'>
        </div>
</div>
<?php require __DIR__ . '/../../drivers/true_driver.php'; ?>
