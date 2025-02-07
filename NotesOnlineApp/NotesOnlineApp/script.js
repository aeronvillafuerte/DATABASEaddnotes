const addBox = document.querySelector(".add-box");
const popupBox = document.querySelector(".popup-box");
const popupTitle = popupBox.querySelector("header p");
const closeIcon = popupBox.querySelector("header i");
const titleTag = popupBox.querySelector("input");
const descTag = popupBox.querySelector("textarea");
const addBtn = popupBox.querySelector("button");

const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

//getting localstorage notes if exist in parsing them   
// to js object else passing an empty array to notes 
const notes = JSON.parse(localStorage.getItem("notes") || "[]");
let isUpdate = false, updateId;

// Add event listener to open popup box
addBox.addEventListener("click", () => {
    titleTag.focus();
    popupBox.classList.add("show");
});

closeIcon.addEventListener("click", () => {
    titleTag.value = "";
    descTag.value = "";
    addBtn.innerText = "Add Note";
    popupTitle.innerText = "Add a new Note";
    popupBox.classList.remove("show");
});

// Function to display notes
function showNotes() {
    // Fetch notes from the server
    fetch('dashboardd.php')
        .then(response => response.json())
        .then(notes => {
            // Clear existing notes
            document.querySelector(".notes-container").innerHTML = "";
            // Iterate over fetched notes and display them
            notes.forEach((note, index) => {
                let liTag = `<li class="note">
                                <div class="details">
                                    <p>${note.title}</p>
                                    <span>${note.content}</span>
                                </div>
                                <div class="bottom-content">
                                    <span>${note.created_at}</span>
                                    <div class="settings">
                                        <i onclick="showMenu(this)" class="uil uil-ellipsis-h"></i>
                                        <ul class="menu">   
                                            <li onclick="updateNote(${index}, '${note.title}', '${note.content}')"><i class="uil uil-pen"></i>Edit</li>
                                            <li onclick="deleteNote(${index})"><i class="uil uil-trash"></i>Delete</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>`;
                document.querySelector(".notes-container").insertAdjacentHTML("beforeend", liTag);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


// Call showNotes to display existing notes
showNotes();

function showMenu(elem) {
    elem.parentElement.classList.add("show");
    document.addEventListener("click", e => {
        // removing show class from the settings menu on document click
        if (e.target.tagName != "I" || e.target != elem) {
            elem.parentElement.classList.remove("show");
        }
    });
}

function deleteNote(noteId) {
    let confirmDel = confirm("Are you sure you want to delete this note?");
    if (!confirmDel) return;

    notes.splice(noteId, 1); // removing selected note from array/tasks
    localStorage.setItem("notes", JSON.stringify(notes)); // saving notes to local storage
    showNotes();
}

function updateNote(noteId, title, desc) {
    isUpdate = true;
    updateId = noteId;
    addBox.click();
    titleTag.value = title;
    descTag.value = desc;
    addBtn.innerText = "Update Note";
    popupTitle.innerText = "Update a Note";
}

// Add event listener to add note
addBtn.addEventListener("click", e => {
    e.preventDefault();
    let noteTitle = titleTag.value;
    let noteDesc = descTag.value;

    if (noteTitle || noteDesc) {
        // Create a FormData object to send the data to the server
        let formData = new FormData();
        formData.append('title', noteTitle);
        formData.append('content', noteDesc);

        // Send the data to the server using fetch API
        fetch('dashboardd.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Log the response from the server
                // Update the UI or perform any necessary actions based on the server response
                // For example, you can reload the notes from the server if needed
                // Or show a success message to the user
            })
            .catch(error => {
                console.error('Error:', error);
            });

        // Clear the form fields
        titleTag.value = '';
        descTag.value = '';

        // Close the popup box
        popupBox.classList.remove("show");
    }

  // Code for pop-up box functionality
// This code handles the behavior of the pop-up box
// You can keep your existing code related to the pop-up box here

// Function to fetch and display notes
function fetchAndDisplayNotes() {
    fetch('retrive.php')
    .then(response => response.json())
    .then(data => {
        const notesList = document.querySelector('.wrapper');
        notesList.innerHTML = ''; // Clear existing notes before adding new ones
        data.forEach(note => {
            const noteElement = document.createElement('li');
            noteElement.classList.add('note');
            noteElement.innerHTML = `
                <div class="details">
                    <p>${note.title}</p>
                    <span>${note.content}</span>
                </div>
                <div class="bottom-content">
                    <span>${note.created_at}</span>
                    <div class="settings">
                        <i class="uil uil-ellipsis-h"></i>
                    </div>
                </div>
            `;
            notesList.appendChild(noteElement);
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Call the function to fetch and display notes when the page loads
window.addEventListener('load', fetchAndDisplayNotes);

});
