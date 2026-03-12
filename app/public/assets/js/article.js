function loadAndDisplayArticles() {
  // Assignment 2 - display articles
  fetch('/api/articles')
    .then(response => response.json())
    .then(articles => {
      console.log(articles);
      const container = document.getElementById('articles-container');
      container.innerHTML = ''; // Clear existing content
      articles.forEach(article => {
        const articleDiv = document.createElement('div');
        articleDiv.classList.add('article');
        articleDiv.innerHTML = `
          <h2>${article.title}</h2>
          <p>${article.content}</p>
          <small>By ${article.author}</small>
        `;
        container.appendChild(articleDiv);
      });
    })
    .catch(error => console.error('Error fetching articles:', error));

  // fetch articles from the API and display them inside the `<div id="articles-container"></div>` element
}

function setupArticleForm() {
  // Assignment 3 - create an article
  const form = document.getElementById('article-form');
  form.addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = {
      title: document.getElementById('title').value,
      content: document.getElementById('content').value,
      author : document.getElementById('author').value
    }
    fetch('/api/articles', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Article created:', data);
      loadAndDisplayArticles(); // Refresh the article list
      form.reset(); // Clear the form
    })
    .catch(error => console.error('Error creating article:', error));
  })
  // get reference to article form and set up event listener
  // on submit, prevent default behavior, get form data, JSON encode it, and send POST request to API
}

window.addEventListener("DOMContentLoaded", () => {
  loadAndDisplayArticles();
  setupArticleForm();
});
