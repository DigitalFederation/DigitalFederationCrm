import DefaultTheme from 'vitepress/theme'
import './custom.css'

export default {
  extends: DefaultTheme,
  enhanceApp({ app, router }) {
    if (typeof window !== 'undefined') {
      // Hide technical content after page loads
      const hideTechnicalContent = () => {
        // Patterns to hide in text content (case insensitive)
        const hidePatterns = [
          // File references
          /\bFile:/i,
          /\bFiles:/i,
          /\bKey Files\b/i,
          /\bKey Components\b/i,
          /\.php\b/,
          /\.js\b/,
          /\.vue\b/,
          /\.blade\.php\b/,
          // Path references
          /src\/Domain\//,
          /app\/Http\//,
          /app\/Livewire\//,
          /app\/Services\//,
          /app\/Policies\//,
          /database\/migrations\//,
          /database\/seeders\//,
          /resources\/views\//,
          /tests\/Feature\//,
          /tests\/Unit\//,
          // Database/technical terms
          /\bFOREIGN KEY\b/i,
          /\bPRIMARY KEY\b/i,
          /\bCREATE TABLE\b/i,
          /\bALTER TABLE\b/i,
          /\bINDEX\b.*\(/,
          /\bUNIQUE KEY\b/i,
          /\bON DELETE CASCADE\b/i,
          /\b_id\s*BIGINT\b/i,
          /\bfactory\(\)/,
          /\bphp artisan\b/i,
          /\bvendor\/bin\//,
          /\bcomposer\b/i,
          /\bnpm run\b/i,
        ]

        // Section headings to hide entirely (with their content)
        const hideSectionPatterns = [
          /Key Files/i,
          /Key Components/i,
          /Database Schema/i,
          /Database Structure/i,
          /File Structure/i,
          /Running Tests/i,
          /Test Coverage/i,
          /Code Quality/i,
          /Migration Guide/i,
          /Technical Decisions/i,
          /Implementation Details/i,
          /Related Tables/i,
        ]

        // Hide matching paragraphs and list items
        document.querySelectorAll('p, li').forEach(el => {
          const text = el.textContent || ''
          if (hidePatterns.some(pattern => pattern.test(text))) {
            el.style.display = 'none'
          }
        })

        // Hide entire sections based on heading
        document.querySelectorAll('h2, h3, h4').forEach(el => {
          const text = el.textContent || ''
          if (hideSectionPatterns.some(pattern => pattern.test(text))) {
            el.style.display = 'none'
            // Hide following siblings until next heading of same or higher level
            const level = parseInt(el.tagName.charAt(1))
            let sibling = el.nextElementSibling
            while (sibling) {
              const siblingLevel = sibling.tagName.match(/^H([1-6])$/)
              if (siblingLevel && parseInt(siblingLevel[1]) <= level) {
                break
              }
              sibling.style.display = 'none'
              sibling = sibling.nextElementSibling
            }
          }
        })

        // Hide table rows containing technical references
        document.querySelectorAll('tr').forEach(row => {
          const text = row.textContent || ''
          if (hidePatterns.some(pattern => pattern.test(text))) {
            row.style.display = 'none'
          }
        })

        // Hide entire tables that look like database schemas
        document.querySelectorAll('table').forEach(table => {
          const text = table.textContent || ''
          if (/CREATE TABLE|FOREIGN KEY|_id\s+BIGINT/i.test(text)) {
            table.style.display = 'none'
          }
        })
      }

      // Run on initial load and route changes
      router.onAfterRouteChanged = () => {
        setTimeout(hideTechnicalContent, 100)
      }

      // Also run on initial page load
      if (document.readyState === 'complete') {
        setTimeout(hideTechnicalContent, 100)
      } else {
        window.addEventListener('load', () => setTimeout(hideTechnicalContent, 100))
      }
    }
  }
}
