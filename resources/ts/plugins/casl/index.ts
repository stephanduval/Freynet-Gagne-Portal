import type { App } from 'vue'

import { createMongoAbility } from '@casl/ability'
import { abilitiesPlugin } from '@casl/vue'
import type { Rule } from './ability'

export default function (app: App) {
  // 1. Reads the rules from localStorage
  const abilityRulesStr = localStorage.getItem('abilityRules')
  const userAbilityRules = abilityRulesStr ? JSON.parse(abilityRulesStr) : []

  // 2. Creates an ability instance with the rules found in localStorage (or empty if none)
  const initialAbility = createMongoAbility(userAbilityRules ?? [])

  // 3. Registers the CASL plugin with Vue, providing the loaded abilities
  app.use(abilitiesPlugin, initialAbility, {
    useGlobalProperties: true,
  })
}
