      
function generateQuestionFields() {
    let total = document.getElementById("total_questions").value;
    const container = document.getElementById("questions-container");
    container.innerHTML = "";

    if (total < 1) return;

    for (let i = 0; i < total; i++) {
        container.innerHTML += `
            <div class="bg-white/60 p-6 rounded-xl border shadow-sm backdrop-blur-sm">

                <h3 class="font-semibold text-lg text-gray-800 mb-4">Question ${i + 1}</h3>

                <label class="block text-gray-800 font-medium">Question Text</label>
                <input 
                    type="text"
                    name="questions[${i}][text]"
                    class="w-full mt-2 mb-4 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                >

                <label class="block text-gray-800 font-medium">Question Type</label>
                <select 
                    name="questions[${i}][type]"
                    class="w-full mt-2 mb-4 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                >
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True/False</option>
                    <option value="short_answer">Short Answer</option>
                </select>

                <label class="block text-gray-800 font-medium">Answer</label>
                <input 
                    type="text"
                    name="questions[${i}][answer]"
                    class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-blue-500"
                >
            </div>
        `;
    }
}

