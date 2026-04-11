import { GridPatternDemo, GridPatternLinearGradient, GridPatternDashed } from "@/components/ui/demo"
import './index.css'

function App() {
  return (
    <div className="flex flex-col gap-8 p-8 bg-slate-950 min-h-screen">
      <header className="mb-8">
        <h1 className="text-4xl font-extrabold text-white tracking-tight">MarkGigs UI</h1>
        <p className="text-slate-400 mt-2">Grid Pattern Component Integration</p>
      </header>
      
      <main className="grid grid-cols-1 gap-12">
        <section>
          <h2 className="text-xl font-semibold text-slate-200 mb-4 px-2">1. Radial Gradient + Skew (Default Demo)</h2>
          <GridPatternDemo />
        </section>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
          <section>
            <h2 className="text-xl font-semibold text-slate-200 mb-4 px-2">2. Linear Gradient Overlay</h2>
            <GridPatternLinearGradient />
          </section>

          <section>
            <h2 className="text-xl font-semibold text-slate-200 mb-4 px-2">3. Dashed Pattern (Custom Spacing)</h2>
            <GridPatternDashed />
          </section>
        </div>
      </main>

      <footer className="mt-12 pt-8 border-t border-slate-800 text-center text-slate-500">
        <p>© 2026 MarkGigs - Next Gen University Talent Platform</p>
      </footer>
    </div>
  )
}

export default App
